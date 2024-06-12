<?php

use Symfony\Component\HttpFoundation\JsonResponse;
use Biblys\Isbn\Isbn as Isbn;
use Biblys\Noosfere\Noosfere;

$pm = new PublisherManager();
$cm = new CollectionManager();
$pom = new PeopleManager();

// IMPORT NOOSFERE
function noosfere($x, $mode = null)
{
    $noosfere = new Noosfere();
    $articles = [];

    $xml = $noosfere->search($x, $mode);
    if (!$xml) {
        return [];
    }

    foreach ($xml->Livre as $n) {
        $a = array();
        $a["article_title"] = (string) $n->Titre;
        $a["article_title"] = str_replace("  ", " – ", $a["article_title"]); // Bug tiret long noosfere
        $a["article_title_original"] = (string) $n->TitreOriginal;

        $a["article_publisher"] = (string) $n->Editeur;
        $a["noosfere_IdEditeur"] = (string) $n->Editeur["IdEditeur"];

        $a["article_collection"] = (string) $n->Collection;
        $a["noosfere_IdCollection"] = (string) $n->Collection["IdCollection"];
        if (empty($a["article_collection"])) {
            $a["article_collection"] = $a["article_publisher"];
            $a["noosfere_IdCollection"] = (string) $n->Editeur["IdEditeur"];
        }
        $a["article_number"] = str_replace(")", "", str_replace("(", "", $n->Reference));

        $a["article_item"] = (string) $n['IdItem'];
        $a["article_noosfere_id"] = (string) $n['IdLivre'];

        $a["article_cycle"] = (string) $n->Serie->TitreSerie;
        if (strstr($a["article_cycle"], "(")) {
            $ex_cycle = explode("(", $a["article_cycle"]);
            $cycle = $ex_cycle[1]."  ".$ex_cycle[0];
            $cycle = str_replace(")", "", $cycle);
            $cycle = ucfirst($cycle);
            $a["article_cycle"] = str_replace("'  ", "'", $cycle);
        }
        $a["article_tome"] = (string) $n->Serie->Volume;
        $a["noosfere_IdSerie"] = (string) $n->Serie["IdSerie"];

        $a["article_cover_import"] = (string) $n->Couverture['LienCouverture'];
        $a["article_cover_import"] = str_replace('http://www.noosfere.org/images/', 'http://images.noosfere.org/', $a['article_cover_import']);

        $a["article_pages"] = (string) $n->Page;

        $a["article_summary"] = null;
        if (!empty($n->Resume) && $n->Resume != "Pas de texte sur la quatriÃ?me de couverture") {
            $a["article_summary"] = '<p>'.str_replace('<br />', '</p><p>', nl2br($n->Resume)).'</p>';
        }

        // Retrait des liens dans la quatrième
        $a["article_summary"] = preg_replace('/<a href=\"(.*?)\">(.*?)<\/a>/', "\\2", $a["article_summary"]);

        // Date parution
        $MoisDL = $n->Parution->MoisParution;
        $MoisDL = str_replace("1", "1er", $MoisDL);
        $MoisDL = str_replace("2", "2Ã?me", $MoisDL);
        $MoisDL = str_replace("3", "3Ã?me", $MoisDL);
        $MoisDL = str_replace("4", "4Ã?me", $MoisDL);
        $MoisDL = makeurl($MoisDL);
        $MoisDLNum = '';
        if ($MoisDL == "janvier") {
            $MoisDLNum = '01';
        } elseif ($MoisDL == "fevrier") {
            $MoisDLNum = '02';
        } elseif ($MoisDL == "mars") {
            $MoisDLNum = '03';
        } elseif ($MoisDL == "avril") {
            $MoisDLNum = '04';
        } elseif ($MoisDL == "mai") {
            $MoisDLNum = '05';
        } elseif ($MoisDL == "juin") {
            $MoisDLNum = '06';
        } elseif ($MoisDL == "juillet") {
            $MoisDLNum = '07';
        } elseif ($MoisDL == "aout") {
            $MoisDLNum = '08';
        } elseif ($MoisDL == "septembre") {
            $MoisDLNum = '09';
        } elseif ($MoisDL == "octobre") {
            $MoisDLNum = '10';
        } elseif ($MoisDL == "novembre") {
            $MoisDLNum = '11';
        } elseif ($MoisDL == "decembre") {
            $MoisDLNum = '12';
        }
        $a["article_pubdate"] = trim($n->Parution->AnneeParution.'-'.$MoisDLNum.'-01');
        $a["article_copyright"] = (string) $n->ParutionOriginale;

        // Contributeurs
        $a['article_authors'] = null;
        if (!empty($n->Intervenants->Intervenant)) {
            $p = 0;
            foreach ($n->Intervenants->Intervenant as $Intervenant) {
                if ($Intervenant->Nom != "ANTHOLOGIE" and $Intervenant->Nom != "REVUE") {
                    if (!empty($Intervenant->Prenom)) {
                        $people[$p]["people_first_name"] = (string) $Intervenant->Prenom;
                    } else {
                        $people[$p]["people_first_name"] = null;
                    }
                    $people[$p]["people_last_name"] = (string) $Intervenant->Nom;
                    $people[$p]["people_name"] = trim($people[$p]["people_first_name"].' '.$people[$p]["people_last_name"]);
                    $people[$p]["people_role"] = (string) $Intervenant['TypeIntervention'];
                    $people[$p]["people_noosfere_id"] = (string) $Intervenant['NooId'];
                    if ($people[$p]["people_role"] == "Auteur") {
                        if (isset($a["article_authors"])) {
                            $a["article_authors"] .= ', ';
                        }
                        $a["article_authors"] .= $people[$p]["people_name"];
                    }
                    $p++;
                }
            }
        }

        // Sommaire
        if (!empty($n->Sommaire)) {
            $a["article_contents"] = '<ul>';
            foreach ($n->Sommaire->EntreeSommaire as $entree) {
                $a["article_contents"] .= '<li>'.$entree->TitreSommaire;
                if (!empty($entree->Intervenants->Intervenant)) {
                    $entry_people = null;
                    foreach ($entree->Intervenants->Intervenant as $Intervenant) {
                        if (!isset($entry_people)) {
                            $entry_people .= ' de ';
                        } else {
                            $entry_people .= ' &amp; ';
                        }
                        $entry_people .= trim($Intervenant->Prenom." ".$Intervenant->Nom);
                        if ($Intervenant['TypeIntervention'] == "Auteur" and $Intervenant->Nom != "REVUE" and $Intervenant->Nom != "COLLECTIF" and $Intervenant->Nom != "ANONYME" and $Intervenant->Nom != "(non mentionnÃ©)") {
                            $people[$p]['people_first_name'] = null;
                            if (!empty($Intervenant->Prenom)) {
                                $people[$p]["people_first_name"] = (string) $Intervenant->Prenom;
                            }
                            $people[$p]["people_last_name"] = (string) $Intervenant->Nom;
                            $people[$p]["people_name"] = trim($people[$p]["people_first_name"].' '.$people[$p]["people_last_name"]);
                            $people[$p]["people_role"] = (string) trim($Intervenant['TypeIntervention']);
                            $people[$p]["people_noosfere_id"] = (string) $Intervenant['NooId'];
                            if ($people[$p]["people_role"] == "Auteur") {
                                if (isset($a["article_authors"])) {
                                    $a["article_authors"] .= ', ';
                                }
                                $a["article_authors"] .= $people[$p]["people_name"];
                            }
                            $p++;
                        }
                    }
                    $a["article_contents"] .= $entry_people;
                }
                $a["article_contents"] .= '</li>';
            }
            $a["article_contents"] .= '</ul>';
        }

        // ISBN
        $a['article_ean'] = null;
        if ($n->ISBN != "nd") {
            $a["article_ean"] = (string) isbn($n->ISBN, 'EAN');
        }

        // Prix
        $categorie = (string) $n->Categorie;
        $price = null;
        if (preg_match("/(\d+,?\d*) FF$/", $categorie, $matches)) {
            $price = $matches[1];
            $price = str_replace(",", ".", $price);
            $price = round((float) $price / 6.55957 * 100);
        } else {
            $price = str_replace(",", ".", $price);
            $price = (float) $price * 100;
        }
        $a["article_price"] = $price;

        $a["article_people"] = $people;

        $a["article_uid"] = $a["article_ean"];
        $a["article_import_source"] = "noosfere";
        $articles[] = $a;
    }
    
    return $articles;
}

// IMPORT AMAZON
function amazon($x)
{
    $public_key = "AKIAJIGPXJ6YGVTJJXGA";
    $private_key = "Av62y5M7mXeY86FI1Yil3CMKQSJd07UVAEbiM9wr";
    $params = array("Operation"=>"ItemSearch","SearchIndex"=>"Books","Keywords"=>$x,"ResponseGroup"=>"Large");
    $params["Service"] = "AWSECommerceService";
    $params["AWSAccessKeyId"] = $public_key;
    $params["Timestamp"] = gmdate("Y-m-d\TH:i:s\Z");
    $params["Version"] = "2009-03-31";
    $params["AssociateTag"] = "librys-20";
    $params["ProductGroup"] = "Book";
    ksort($params);
    $canonicalized_query = array();
    foreach ($params as $param=>$value) {
        $param = str_replace("%7E", "~", rawurlencode($param));
        $value = str_replace("%7E", "~", rawurlencode($value));
        $canonicalized_query[] = $param."=".$value;
    }
    $canonicalized_query = implode("&", $canonicalized_query);
    $string_to_sign = "GET"."\n"."ecs.amazonaws.fr"."\n"."/onca/xml"."\n".$canonicalized_query;
    $signature = base64_encode(hash_hmac("sha256", $string_to_sign, $private_key, true));
    $signature = str_replace("%7E", "~", rawurlencode($signature));
    $request = "http://"."ecs.amazonaws.fr"."/onca/xml"."?".$canonicalized_query."&Signature=".$signature;
    $try = 0;
    while (empty($response)) {
        $response = @file_get_contents($request);
        if (empty($response)) {
            sleep(0.1);
        }
        $try++;
        if ($try > 10) {
            break;
        }
    }
    if ($response === false) {
        echo '<p class="erreur">Erreur r&eacute;ponse Amazon pour '.$x.' (<a href="'.$request.'">requ&ecirc;te</a>)</p>';
    } else {
        $pxml = simplexml_load_string($response);
        if ($pxml === false) {
            return false;
        } // no xml
        elseif (!empty($pxml->Error->Code)) {
            die('{"error":"Erreur importation Amazon : '.$pxml->Error->Message.'"}');
        } elseif ($pxml->Items->Request->IsValid == "False") {
            die('{"error":"Erreur importation Amazon : '.$pxml->Items->Request->Errors->Error->Message.'"}');
        } else {
            foreach ($pxml->Items->Item as $item) {
                $a = array();
                $a["article_title"] = (string) $item->ItemAttributes->Title;
                $a["article_cover_import"] = (string) $item->LargeImage->URL;
                $a["article_asin"] = (string) $item->ASIN;
                $a["article_ean"] = (string) $item->ItemAttributes->EAN;
                $a["article_price"] = (string) $item->ItemAttributes->ListPrice->Amount;
                $a["article_pages"] = (string) $item->ItemAttributes->NumberOfPages;
                $a["article_pubdate"] = (string) $item->ItemAttributes->PublicationDate;
                $a["article_shaping"] = (string) $item->ItemAttributes->Biding;
                //$a["article_weight"] = round($item->ItemAttributes->PackageDimensions->Weight * 4.5359237);
                $a["article_collection"] = (string) $item->ItemAttributes->Publisher;

                $p = 0;
                $a['article_authors'] = null;
                foreach ($item->ItemAttributes->Author as $author) {
                    $a["article_people"][$p]["people_name"] = (string) $author;
                    $a["article_people"][$p]["people_role"] = "Auteur";
                    if (isset($a["article_authors"])) {
                        $a["article_authors"] .= ', ';
                    }
                    $a["article_authors"] .= (string) $author;
                    $p++;
                }
                foreach ($item->ItemAttributes->Creator as $creator) {
                    $a["article_people"][$p]["people_name"] = (string) $creator;
                    $a["article_people"][$p]["people_role"] = (string) $creator->attributes()->Role;
                    $p++;
                }

                $a["article_uid"] = $a["article_ean"];
                $a["article_import_source"] = "amazon";
                $articles[] = $a;
            }
        }
    }
    if (isset($articles)) {
        return $articles;
    }
}

if ($_GET["mode"] == "search") { // Mode recherche
    $r = null;
    $articles_noosfere = noosfere($_GET["q"]); // ne fonctionne plus le 16 avril 2015
    
    // If query param is an EAN, try as an ISBN-10
    $isbnQ = new ISBN($_GET['q']);
    if (count($articles_noosfere) === 0 && $isbnQ->isValid()) {
        $isbn10 = $isbnQ->format('ISBN-10');
        $articleNoosfereIsbn10 = noosfere($isbn10);
        $articles_noosfere = (array_merge($articles_noosfere, $articleNoosfereIsbn10));
    }

    //$articles_amazon = amazon($_GET["q"]);
    $results = 0;
    $additional_results = null;
    $r .= '<div id="results" class="hidden">';
    if ($articles_noosfere) {
        foreach ($articles_noosfere as $a) {
            $result = '
                <div data-ean="'.$a["article_ean"].'" data-asin="'.(isset($a["article_asin"]) ? $a['article_asin'] : null).'" data-noosfere_id="'.$a["article_noosfere_id"].'" class="article-thumb article-import pointer">
                    <img src="'.$a["article_cover_import"].'" height="85" class="article-thumb-cover" />
                    <div class="article-thumb-data">
                        <h3>'.$a["article_title"].'</h3>
                        <p>
                            de '.truncate($a["article_authors"], 65, '...', true, true).'<br />
                            coll. '.$a["article_collection"].' '.numero($a["article_number"]).' ('.$a["article_publisher"].')<br />
                            ISBN : '.isbn($a["article_ean"], 'ISBN13').'
                        </p>
                    </div>
                </div>
            ';
            if ($results < 3) {
                $r .= $result;
            } else {
                $additional_results .= $result;
            }
            $results++;
        }
    }
    if (isset($articles_amazon)) {
        foreach ($articles_amazon as $a) {
            $result = '
                <div data-ean="'.$a["article_ean"].'" data-asin="'.$a["article_asin"].'" data-noosfere_id="'.(isset($a["article_noosfere_id"]) ? $a['article_noosfere_id'] : null).'" class="article-thumb article-import pointer" title="Importer de '.$a["article_import_source"].'">
                    <img src="'.$a["article_cover_import"].'" height="85" class="article-thumb-cover" />
                    <h3>'.truncate($a["article_title"], 50, '...', true).'</h3>
                    <p>
                        de '.truncate($a["article_authors"], 65, '...', true).'<br />
                        coll. '.$a["article_collection"].'<br />
                        ISBN : '.isbn($a["article_ean"], 'ISBN13').'
                    </p>
                </div>
            ';

            if ($results < 3) {
                $r .= $result;
            } else {
                $additional_results .= $result;
            }
            $results++;
        }
    }
    $r .= '</div>';
    if (empty($results)) {
        $r .= '<p class="center">Aucun r&eacute;sultat dans les bases externes.</p>';
    }
    if (isset($additional_results)) {
        $r .= '<div id="additionalResults" class="hidden">'.$additional_results.'</div><h3 id="showAllResults" class="toggleThis center pointer">Afficher plus de r&eacute;sultats...</h3>';
    }
    
    $response = new JsonResponse(['result' => $r]);
    $response->send();
} elseif ($_GET["mode"] == "import") { // Mode import
    if (!empty($_GET["noosfere_id"])) { // Importation de fiche noosfere
        $n = noosfere($_GET["noosfere_id"], 'noosfere_id');
        $n = $n[0];
        if (!empty($n["article_ean"])) { // Infos complementaires d'Amazon si EAN
            //$a = amazon($n["article_ean"]);
            //$a = $a[0];
            if (!empty($a)) {
                foreach ($a as $key => $val) { // Champ a recuperer d'Amazon uniquement si vide chez noosfere
                    if (empty($n[$key])) {
                        $n[$key] = $val;
                    }
                }
            }
            // Champ a recuperer en priorite chez Amazon
            if (isset($a["article_pubdate"])) {
                $n["article_pubdate"] = $a["article_pubdate"];
            }
            if (isset($a["article_cover_import"])) {
                $n["article_cover_import"] = $a["article_cover_import"];
            }
            if (isset($a["article_price"])) {
                $n["article_price"] = $a["article_price"];
            }
        }
        $x = $n;
    } elseif (!empty($_GET["asin"])) {
        //$x = amazon($_GET["asin"]);
        //$x = $x[0];
    }

    // Reconnaissance d'editeur
    if (isset($x["article_collection"])) {
        if (!isset($x['noosfere_IdEditeur']) || empty($x['noosfere_IdEditeur'])) {
            $x['noosfere_IdEditeur'] = null;
        }
        if (!isset($x['article_publisher']) || empty($x['article_publisher'])) {
            $x['article_publisher'] = null;
        }

        $publishers = $_SQL->prepare(
            "SELECT `publisher_id`, `publisher_name` FROM `publishers` 
                WHERE (`publisher_noosfere_id` = :noosfere_IdEditeur OR 
                    `publisher_name` = :publisher_name) AND
                    `publisher_deleted` IS NULL
                ORDER BY `publisher_noosfere_id` LIMIT 1"
        );
        $publishers->execute(
            [
                'noosfere_IdEditeur' => $x["noosfere_IdEditeur"],
                'publisher_name' => $x["article_publisher"],
            ]
        );
        
        // Si l'editeur existe deja en base, on recupere les infos
        if ($p = $publishers->fetch(PDO::FETCH_ASSOC)) {
            $publisher = $pm->getById($p['publisher_id']);
            $x["publisher_id"] = $p["publisher_id"];
            $x["article_publisher"] = $p["publisher_name"];
            if (!empty($x["noosfere_IdEditeur"])) {
                $publisher->set('publisher_noosfere_id', $x["noosfere_IdEditeur"]);
                $pm->update($publisher);
            }
        }

        // Si l'editeur n'existe pas, mais qu'on a un id noosfere, on la cree
        elseif (!empty($x["noosfere_IdEditeur"])) {
            $publisher = $pm->create([
                "publisher_name" => $x["article_publisher"],
                "publisher_noosfere_id" => $x["noosfere_IdCollection"], 
            ]);
            $x["publisher_id"] = $publisher->get('id');
        // Sinon, on ne met pas de editeur
        } else {
            $x["article_publisher"] = null;
        }
    }
    if (empty($x["article_publisher"])) {
        unset($x["article_publisher"]);
    }


    // Reconnaissance de collection
    if (isset($x["article_collection"]) && isset($x["publisher_id"])) {
        
        // Filtres de collections noosfere
        $correctId = Noosfere::getCorrectIdFor($x["noosfere_IdCollection"]);
        if ($correctId) {
            $x["noosfere_IdCollection"] = $correctId;
        }

        $collection = false;

        // Try to get collection by noosfere id
        if (isset($x["noosfere_IdCollection"])) {
            $collection = $cm->get(['collection_noosfere_id' => $x["noosfere_IdCollection"]]);
        }

        // Try to get collection by name and publisher
        if (!$collection) {
            $collection = $cm->get([
                'collection_name' => $x["article_collection"],
                'publisher_id' => $x["publisher_id"],
            ]);
        }

        // If collection already exists in db, get info
        if ($collection) {
            $x["collection_id"] = $collection->get("id");
            $x["article_collection"] = $collection->get("name");
            $x["article_publisher"] = $collection->get("publisher");
            $x["pricegrid_id"] = $collection->get("pricegrid_id");

            // If noosfere is set, update collection
            if (!$collection->has('collection_noosfere_id') && !empty($x["noosfere_IdCollection"])) {
                $collection->set('collection_noosfere_id', $x["noosfere_IdCollection"]);
                $cm->update($collection);
            }
        } 
        
        // If collection does not exist but has a noosfere id, let's create it
        elseif (!empty($x["noosfere_IdCollection"])) {
           
            // Si la collection n'existe pas, mais qu'on a un id noosfere, on la cree
            $collectionParams = [
                "publisher_id" => $publisher->get('id'),
                "collection_name" => $x["article_collection"],
                "collection_noosfere_id" => $x["noosfere_IdCollection"]
            ];

            $collection = $cm->create($collectionParams);
            $x["collection_id"] = $collection->get('id');
        }
    }

    // Reconnaissance de cycle
    if (!empty($x["article_cycle"])) {
        $cym = new CycleManager();
        $cycle = $cym->get(array('cycle_noosfere_id' => $x["noosfere_IdSerie"]));
        if (!$cycle) {
            $cycle = $cym->get(array('cycle_name' => $x["article_cycle"]));
            if (!$cycle) {
                $x["cycle_url"] = makeurl($x["article_cycle"]);
                $cycle = $cym->create(array(
                    'cycle_name' => $x['article_cycle'],
                    'cycle_url' => makeurl($x["article_cycle"]),
                    'cycle_noosfere_id' => $x["noosfere_IdSerie"]
                ));
            }
        }

        $x['article_cycle'] = $cycle->get('name');
        $x['cycle_id'] = $cycle->get('id');
    }

    // Reconnaissance des contributeurs
    unset($x["article_authors"]);
    if (!empty($x["article_people"])) {
        $x["article_people"] = array_unique($x["article_people"], SORT_REGULAR);
        foreach ($x["article_people"] as $k => $c) {

            // Reconnaissance des jobs
            if (!empty($c["people_role"])) {
                $jobs = $_SQL->prepare(
                    "SELECT `job_id` FROM `jobs` 
                    WHERE `job_name` = :people_role OR 
                        `job_other_names` LIKE :like_people_role LIMIT 1"
                );
                $jobs->execute(
                    [
                       'people_role' => $c['people_role'],
                       'like_people_role' => '%'.$c['people_role'].'%',
                    ]
                );

                if ($j = $jobs->fetch(PDO::FETCH_ASSOC)) {
                    $x["article_people"][$k]["job_id"] = $j["job_id"];
                }
            }

            if (!isset($c['people_noosfere_id']) || empty($c['people_noosfere_id'])) {
                $c['people_noosfere_id'] = null;
            }

            $people = $_SQL->prepare(
                "SELECT `people_id`, `people_name` FROM `people` 
                WHERE `people_noosfere_id` = :people_noosfere_id OR 
                    `people_name` = :people_name OR 
                    `people_url` = :people_url
                ORDER BY `people_noosfere_id` DESC LIMIT 1"
            );
            $people->execute(
                [
                    'people_noosfere_id' => $c['people_noosfere_id'],
                    'people_name' => $c['people_name'],
                    'people_url' => makeurl($c["people_name"]),
                ]
            );
            
            // Si le contributeur existe deja en base, on recupere les infos
            if ($p = $people->fetch(PDO::FETCH_ASSOC)) {
                $contributor = $pom->getById($p['people_id']);
                $x["article_people"][$k]["people_name"] = $p["people_name"];
                $x["article_people"][$k]["people_id"] = $p["people_id"];
                if (!empty($c["people_noosfere_id"])) {
                    $contributor->set('people_noosfere_id', $c['people_noosfere_id']);
                    $pom->update($contributor);
                }
            } elseif (!empty($c["people_noosfere_id"])) { // Si le contributeur n'existe pas, mais qu'on a un id noosfere, on la cree
                $c["people_url"] = makeurl($c["people_name"]);
                $contributor = $pom->create(
                    [
                        'people_first_name' => $c['people_first_name'],
                        'people_last_name' => $c['people_last_name'],
                        'people_noosfere_id' => $c['people_noosfere_id'],
                    ]
                );
                $x["article_people"][$k]["people_id"] = $contributor->get('id');
                $x["article_people"][$k]["people_name"] = $contributor->getName();
            }
            
            // S'il manque des infos, on n'ajoute pas le contributeur au livre
            if (empty($x["article_people"][$k]["people_id"]) 
                || empty($x["article_people"][$k]["people_name"]) 
                || empty($x["article_people"][$k]["job_id"])
            ) {
                unset($x["article_people"][$k]);
            }
        }
    }

    // Reconnaissance des catégories
    if (isset($x["pricegrid_id"]) && isset($x["article_price"])) {
        $prices = $_SQL->prepare(
            "SELECT `price_cat` FROM `prices` 
            WHERE `price_amount` = :price_amount AND 
                `pricegrid_id` = :pricegrid_id LIMIT 1"
        );
        $prices->execute(
            [
                'price_amount' => $x['article_price'],
                'pricegrid_id' => $x['pricegrid_id']
            ]
        );
        if ($p = $prices->fetch(PDO::FETCH_ASSOC)) {
            $x["article_category"] = $p["price_cat"];
        }
    }

    // Resultat en json
    echo str_replace("\u0092", "\u2019", json_encode($x));
} else {
    echo '<pre>'.print_r($articles, 1).'</pre>';
}


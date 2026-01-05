<?php

namespace App\DataFixtures;

use App\Entity\RadioType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class RadioTypeFixtures extends Fixture
{
	public function load(ObjectManager $manager): void
	{
		$radios = [

			// RADIO – THORAX & ABDOMEN
			['Radiographie thoracique face', 'Exploration pulmonaire standard', 'Thorax'],
			['Radiographie thoracique profil', 'Vue latérale du thorax', 'Thorax'],
			['Radiographie des côtes', 'Recherche fracture costale', 'Thorax'],
			['Radiographie abdominale sans préparation', 'Occlusion, constipation', 'Abdomen'],
			['Radiographie abdominale debout', 'Niveaux hydro-aériques', 'Abdomen'],

			// RADIO – CRÂNE & FACE
			['Radiographie du crâne face', 'Traumatisme crânien', 'Crâne'],
			['Radiographie du crâne profil', 'Vue latérale crâne', 'Crâne'],
			['Radiographie des sinus', 'Sinusite', 'Sinus'],
			['Radiographie des os de la face', 'Traumatisme facial', 'Face'],

			// RADIO – MEMBRE SUPÉRIEUR
			['Radiographie de l’épaule', 'Douleur ou traumatisme', 'Épaule'],
			['Radiographie du bras', 'Fracture humérus', 'Bras'],
			['Radiographie du coude', 'Luxation ou fracture', 'Coude'],
			['Radiographie de l’avant-bras', 'Radius / cubitus', 'Avant-bras'],
			['Radiographie du poignet', 'Fracture du carpe', 'Poignet'],
			['Radiographie de la main', 'Traumatisme ou arthrose', 'Main'],
			['Radiographie des doigts', 'Fracture phalange', 'Doigts'],
			['Radiographie de la clavicule', 'Fracture clavicule', 'Clavicule'],

			// RADIO – MEMBRE INFÉRIEUR
			['Radiographie du bassin', 'Analyse osseuse', 'Bassin'],
			['Radiographie de la hanche', 'Coxarthrose / fracture', 'Hanche'],
			['Radiographie du fémur', 'Fracture diaphysaire', 'Fémur'],
			['Radiographie du genou', 'Arthrose / traumatisme', 'Genou'],
			['Radiographie de la jambe', 'Tibia / péroné', 'Jambe'],
			['Radiographie de la cheville', 'Entorse / fracture', 'Cheville'],
			['Radiographie du pied', 'Douleur plantaire', 'Pied'],
			['Radiographie des orteils', 'Fracture phalange', 'Orteils'],

			// RADIO – RACHIS
			['Radiographie cervicale face', 'Douleurs cervicales', 'Cou'],
			['Radiographie cervicale profil', 'Traumatologie cervicale', 'Cou'],
			['Radiographie dorsale', 'Scoliose dorsale', 'Dos'],
			['Radiographie lombaire', 'Lombalgies', 'Dos'],
			['Radiographie du rachis entier', 'Bilan scoliose', 'Rachis'],
			['IRM cérébrale', 'Pathologie neurologique', 'Cerveau'],
			['IRM hypophysaire', 'Adénome hypophyse', 'Cerveau'],
			['IRM orbitaire', 'Nerf optique', 'Yeux'],
			['IRM cervicale', 'Névralgie cervico-brachiale', 'Cou'],
			['IRM dorsale', 'Compression médullaire', 'Dos'],
			['IRM lombaire', 'Hernie discale', 'Dos'],
			['IRM du rachis entier', 'Bilan neurologique', 'Rachis'],
			['IRM thoracique', 'Médiastin', 'Thorax'],
			['IRM abdominale', 'Foie / pancréas', 'Abdomen'],
			['IRM pelvienne', 'Gynécologie / prostate', 'Bassin'],
			['IRM prostatique', 'Cancer prostate', 'Prostate'],
			['IRM utérine', 'Fibromes', 'Utérus'],
			['IRM mammaire', 'Cancer du sein', 'Seins'],
			['IRM cardiaque', 'Fonction cardiaque', 'Cœur'],

			['IRM de l’épaule', 'Rupture coiffe', 'Épaule'],
			['IRM du coude', 'Tendinopathie', 'Coude'],
			['IRM du poignet', 'Canal carpien', 'Poignet'],
			['IRM de la main', 'Lésions ligamentaires', 'Main'],
			['IRM de la hanche', 'Nécrose tête fémorale', 'Hanche'],
			['IRM du genou', 'Ménisque / LCA', 'Genou'],
			['IRM de la cheville', 'Ligaments', 'Cheville'],
			['IRM du pied', 'Pathologie plantaire', 'Pied'],
			['Échographie abdominale', 'Foie, reins, rate', 'Abdomen'],
			['Échographie hépatique', 'Bilan hépatique', 'Foie'],
			['Échographie rénale', 'Calculs, reins', 'Reins'],
			['Échographie vésicale', 'Rétention urinaire', 'Vessie'],
			['Échographie pelvienne', 'Bilan gynécologique', 'Bassin'],
			['Échographie endovaginale', 'Utérus / ovaires', 'Utérus'],
			['Échographie prostatique', 'Prostate', 'Prostate'],
			['Échographie testiculaire', 'Douleurs testiculaires', 'Testicules'],
			['Échographie mammaire', 'Seins', 'Seins'],
			['Échographie thyroïdienne', 'Nodules', 'Cou'],
			['Échographie cervicale', 'Ganglions', 'Cou'],
			['Échographie obstétricale T1', 'Grossesse début', 'Utérus'],
			['Échographie obstétricale T2', 'Morphologie fœtale', 'Utérus'],
			['Échographie obstétricale T3', 'Croissance fœtale', 'Utérus'],

			['Doppler veineux membres inférieurs', 'TVP', 'Jambes'],
			['Doppler artériel membres inférieurs', 'Artérite', 'Jambes'],
			['Doppler veineux membres supérieurs', 'Thrombose', 'Bras'],
			['Doppler carotidien', 'Sténose carotides', 'Cou'],
			['Doppler rénal', 'Flux rénal', 'Reins'],
			['Doppler hépatique', 'Flux portal', 'Foie'],
			['Échocardiographie', 'Fonction cardiaque', 'Cœur'],
			['Mammographie bilatérale', 'Dépistage cancer sein', 'Seins'],
			['Mammographie unilatérale', 'Exploration ciblée', 'Seins'],
			['Ostéodensitométrie', 'Densité osseuse', 'Os'],
			['Scintigraphie osseuse', 'Métastases osseuses', 'Os'],
			['Scintigraphie thyroïdienne', 'Fonction thyroïde', 'Cou'],
			['PET-Scan', 'Bilan oncologique', 'Corps entier'],
			['Radiologie interventionnelle', 'Gestes guidés', 'Multi-zones'],





		];

		foreach ($radios as [$nom, $description, $zone]) {
			$radio = new RadioType();
			$radio->setNom($nom);
			$radio->setDescription($description);
			$radio->setZoneCorps($zone);

			$manager->persist($radio);
		}

		$manager->flush();
	}
}

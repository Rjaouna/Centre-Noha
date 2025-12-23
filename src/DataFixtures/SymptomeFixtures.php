<?php

namespace App\DataFixtures;

use App\Entity\Symptome;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class SymptomeFixtures extends Fixture
{
	public function load(ObjectManager $manager): void
	{
		$data = [

			// 🟠 Abdomen & système digestif
			['Prurit anal', 'Abdomen & système digestif', 'Démangeaisons persistantes de la région anale, souvent liées à des causes dermatologiques, infectieuses ou digestives.'],
			['Sensation de boule dans la gorge (globus pharyngé)', 'Abdomen & système digestif', 'Impression de corps étranger dans la gorge sans obstruction réelle.'],
			['Constipation (adulte / enfant)', 'Abdomen & système digestif', 'Diminution de la fréquence des selles ou difficulté à les évacuer.'],
			['Diarrhée (adulte / enfant)', 'Abdomen & système digestif', 'Augmentation de la fréquence et de la liquidité des selles.'],
			['Douleur abdominale aiguë', 'Abdomen & système digestif', 'Douleur soudaine et intense de l’abdomen, pouvant évoquer une urgence médicale.'],
			['Douleur abdominale chronique', 'Abdomen & système digestif', 'Douleur abdominale persistante ou récurrente depuis plus de 3 mois.'],
			['Dyspepsie (indigestion)', 'Abdomen & système digestif', 'Gêne ou douleur épigastrique associée à des troubles digestifs.'],
			['Dysphagie', 'Abdomen & système digestif', 'Difficulté ou douleur lors de la déglutition.'],
			['Gaz et ballonnements', 'Abdomen & système digestif', 'Sensation de distension abdominale liée à l’accumulation de gaz.'],
			['Hoquet', 'Abdomen & système digestif', 'Contractions involontaires et répétées du diaphragme.'],
			['Nausées et vomissements', 'Abdomen & système digestif', 'Sensation de malaise gastrique avec ou sans rejet du contenu gastrique.'],

			// 🟠 Appareil génito-urinaire
			['Brûlure à la miction (dysurie)', 'Appareil génito-urinaire', 'Douleur ou sensation de brûlure lors de l’émission d’urine.'],
			['Pollakiurie', 'Appareil génito-urinaire', 'Augmentation anormale de la fréquence des mictions.'],
			['Polyurie', 'Appareil génito-urinaire', 'Augmentation excessive du volume des urines sur 24 heures.'],
			['Rétention urinaire', 'Appareil génito-urinaire', 'Impossibilité partielle ou totale de vider la vessie.'],
			['Incontinence urinaire (adulte / enfant)', 'Appareil génito-urinaire', 'Perte involontaire d’urine.'],
			['Douleur scrotale', 'Appareil génito-urinaire', 'Douleur localisée au niveau du scrotum.'],
			['Gonflement du scrotum', 'Appareil génito-urinaire', 'Augmentation de volume du scrotum, parfois associée à une inflammation ou une pathologie testiculaire.'],
			['Érection persistante (priapisme)', 'Appareil génito-urinaire', 'Érection prolongée et douloureuse sans stimulation sexuelle.'],
			['Sang dans les urines (hématurie)', 'Appareil génito-urinaire', 'Présence de sang visible ou microscopique dans les urines.'],

			// 🟠 Articulations & muscles
			['Douleur articulaire mono-articulaire', 'Articulations & muscles', 'Douleur touchant une seule articulation.'],
			['Douleur articulaire poly-articulaire', 'Articulations & muscles', 'Douleur touchant plusieurs articulations simultanément.'],
			['Douleurs lombaires', 'Articulations & muscles', 'Douleur localisée au bas du dos.'],
			['Douleurs cervicales', 'Articulations & muscles', 'Douleur au niveau du cou.'],
			['Crampes musculaires', 'Articulations & muscles', 'Contractions musculaires involontaires et douloureuses.'],
			['Raideur articulaire', 'Articulations & muscles', 'Limitation ou difficulté de mobilisation d’une articulation.'],

			// 🟠 Cerveau & système nerveux
			['Céphalées', 'Cerveau & système nerveux', 'Douleurs localisées au niveau de la tête.'],
			['Étourdissements', 'Cerveau & système nerveux', 'Sensation de tête légère ou d’instabilité.'],
			['Vertiges', 'Cerveau & système nerveux', 'Illusion de mouvement ou de rotation de l’environnement.'],
			['Syncope', 'Cerveau & système nerveux', 'Perte de connaissance brutale et transitoire.'],
			['Hypotension orthostatique', 'Cerveau & système nerveux', 'Baisse de la tension artérielle lors du passage à la position debout.'],
			['Engourdissement', 'Cerveau & système nerveux', 'Diminution ou perte de la sensibilité d’une zone du corps.'],
			['Paresthésies', 'Cerveau & système nerveux', 'Sensations anormales comme des fourmillements ou picotements.'],
			['Perte de mémoire', 'Cerveau & système nerveux', 'Diminution des capacités de mémorisation.'],
			['Confusion', 'Cerveau & système nerveux', 'Altération de la conscience et des capacités cognitives.'],
			['Faiblesse généralisée', 'Cerveau & système nerveux', 'Diminution globale de la force musculaire.'],
			['Insomnie', 'Cerveau & système nerveux', 'Difficulté à s’endormir ou à maintenir le sommeil.'],
			['Somnolence diurne excessive', 'Cerveau & système nerveux', 'Besoin excessif de dormir pendant la journée.'],

			// 🟠 Thorax & système respiratoire
			['Douleur thoracique', 'Thorax & système respiratoire', 'Douleur ressentie au niveau de la poitrine.'],
			['Essoufflement (dyspnée)', 'Thorax & système respiratoire', 'Difficulté ou gêne respiratoire.'],
			['Toux (adulte / enfant)', 'Thorax & système respiratoire', 'Expiration brutale et sonore visant à dégager les voies aériennes.'],
			['Toux sanglante (hémoptysie)', 'Thorax & système respiratoire', 'Expectoration de sang provenant des voies respiratoires.'],
			['Sifflement respiratoire', 'Thorax & système respiratoire', 'Bruit aigu lié à un rétrécissement des bronches.'],
			['Stridor', 'Thorax & système respiratoire', 'Bruit respiratoire aigu traduisant une obstruction des voies aériennes supérieures.'],
			['Oppression thoracique', 'Thorax & système respiratoire', 'Sensation de poids ou de compression dans la poitrine.'],

			// 🟠 ORL
			['Douleur auriculaire (otalgie)', 'ORL', 'Douleur localisée à l’oreille.'],
			['Écoulement auriculaire (otorrhée)', 'ORL', 'Écoulement de liquide par le conduit auditif.'],
			['Écoulement nasal (rhinorrhée)', 'ORL', 'Écoulement de mucus par le nez.'],
			['Congestion nasale', 'ORL', 'Obstruction des fosses nasales.'],
			['Maux de gorge', 'ORL', 'Douleur ou irritation de la gorge.'],
			['Enrouement', 'ORL', 'Modification de la voix la rendant rauque.'],
			['Perte de l’odorat (anosmie)', 'ORL', 'Diminution ou disparition de l’odorat.'],
			['Perte auditive', 'ORL', 'Diminution de la capacité à entendre.'],
			['Acouphènes', 'ORL', 'Perception de bruits sans source externe.'],
			['Ronflement', 'ORL', 'Bruit respiratoire nocturne lié à une obstruction partielle des voies aériennes.'],
			['Saignement de nez (épistaxis)', 'ORL', 'Écoulement de sang par le nez.'],

			// 🟠 Œil
			['Douleur oculaire', 'Œil', 'Douleur ressentie au niveau de l’œil.'],
			['Rougeur oculaire', 'Œil', 'Rougeur de l’œil liée à une inflammation ou irritation.'],
			['Vision floue', 'Œil', 'Diminution de la netteté de la vision.'],
			['Vision double (diplopie)', 'Œil', 'Perception simultanée de deux images d’un même objet.'],
			['Perte de vision aiguë', 'Œil', 'Diminution brutale de la vision.'],
			['Larmoiement', 'Œil', 'Production excessive de larmes.'],
			['Gonflement des paupières', 'Œil', 'Œdème des paupières.'],
			['Anisocorie', 'Œil', 'Différence de taille entre les deux pupilles.'],
			['Corps flottants', 'Œil', 'Perception de taches mobiles dans le champ visuel.'],
			['Exophtalmie', 'Œil', 'Protrusion anormale du globe oculaire.'],

			// 🟠 Peau & cheveux
			['Prurit', 'Peau & cheveux', 'Démangeaisons cutanées.'],
			['Urticaire', 'Peau & cheveux', 'Éruption cutanée avec plaques rouges et prurigineuses.'],
			['Éruption cutanée', 'Peau & cheveux', 'Apparition de lésions visibles sur la peau.'],
			['Alopécie', 'Peau & cheveux', 'Chute partielle ou totale des cheveux ou des poils.'],
			['Hirsutisme', 'Peau & cheveux', 'Pilosité excessive chez la femme.'],
			['Ecchymoses', 'Peau & cheveux', 'Taches violacées dues à une extravasation sanguine.'],
			['Œdème', 'Peau & cheveux', 'Gonflement lié à une accumulation de liquide dans les tissus.'],

			// 🟠 Saignements
			['Ecchymoses et hémorragies', 'Saignements', 'Saignements cutanés ou profonds anormaux.'],
			['Épistaxis', 'Saignements', 'Saignement nasal.'],
			['Saignement gastro-intestinal', 'Saignements', 'Perte de sang par le tube digestif.'],
			['Saignements vaginaux (métrorragies)', 'Saignements', 'Saignements en dehors des règles.'],
			['Hématurie', 'Saignements', 'Présence de sang dans les urines.'],
			['Hémospermie', 'Saignements', 'Présence de sang dans le sperme.'],
			['Hémoptysie', 'Saignements', 'Expectoration de sang d’origine respiratoire.'],

			// 🟠 Symptômes généraux
			['Fièvre', 'Symptômes généraux', 'Élévation anormale de la température corporelle.'],
			['Fatigue', 'Symptômes généraux', 'Sensation persistante de manque d’énergie.'],
			['Amaigrissement', 'Symptômes généraux', 'Perte de poids involontaire.'],
			['Prise de poids', 'Symptômes généraux', 'Augmentation anormale du poids corporel.'],
			['Sueurs nocturnes', 'Symptômes généraux', 'Transpiration excessive pendant la nuit.'],
			['Frissons', 'Symptômes généraux', 'Contractions musculaires involontaires liées à une sensation de froid.'],
			['Anorexie', 'Symptômes généraux', 'Perte ou diminution de l’appétit.'],
			['Gonflement des ganglions lymphatiques', 'Symptômes généraux', 'Augmentation de volume des ganglions.'],
			['Malaise', 'Symptômes généraux', 'Sensation de faiblesse ou d’inconfort général.'],

			// 🟠 Gynécologie & obstétrique
			['Absence de règles (aménorrhée)', 'Gynécologie & obstétrique', 'Arrêt ou absence des menstruations.'],
			['Crampes menstruelles (dysménorrhée)', 'Gynécologie & obstétrique', 'Douleurs pelviennes pendant les règles.'],
			['Douleur pelvienne', 'Gynécologie & obstétrique', 'Douleur localisée au bassin.'],
			['Saignement vaginal', 'Gynécologie & obstétrique', 'Écoulement sanguin d’origine vaginale.'],
			['Œdème de grossesse', 'Gynécologie & obstétrique', 'Gonflement lié à la rétention hydrique pendant la grossesse.'],
			['Écoulement du mamelon', 'Gynécologie & obstétrique', 'Écoulement anormal par le mamelon.'],
			['Nodules mammaires', 'Gynécologie & obstétrique', 'Masses palpables au niveau du sein.'],

			// 🟠 Symptômes chez l’enfant
			['Toux chez l’enfant', 'Symptômes chez l’enfant', 'Toux survenant chez le nourrisson ou l’enfant.'],
			['Pleurs inexpliqués', 'Symptômes chez l’enfant', 'Pleurs persistants sans cause évidente.'],
			['Fièvre chez l’enfant', 'Symptômes chez l’enfant', 'Élévation de la température corporelle chez l’enfant.'],
			['Constipation infantile', 'Symptômes chez l’enfant', 'Difficulté d’émission des selles chez l’enfant.'],
			['Diarrhée infantile', 'Symptômes chez l’enfant', 'Selles liquides fréquentes chez l’enfant.'],
		];

		foreach ($data as [$name, $category, $description]) {
			$symptome = new Symptome();
			$symptome->setName($name);
			$symptome->setCategory($category);
			$symptome->setDescription($description);
			$manager->persist($symptome);
		}

		$manager->flush();
	}
}

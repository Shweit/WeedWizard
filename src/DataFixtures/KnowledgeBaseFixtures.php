<?php

namespace App\DataFixtures;

use App\Entity\KnowledgeBase;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class KnowledgeBaseFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $entry = new KnowledgeBase();
        $entry->setSite('knowledge_base');
        $entry->setCategorie('cannabis_tips');
        $entry->setArticleName('Was ist Cannabis?');
        $entry->setArticleContent('<p>Cannabis ist eine Pflanze, die für ihre psychoaktiven und medizinischen Eigenschaften bekannt ist. Die Hauptbestandteile von Cannabis sind Tetrahydrocannabinol (THC) und Cannabidiol (CBD). Während THC für die berauschende Wirkung verantwortlich ist, hat CBD keine psychoaktive Wirkung und wird häufig für medizinische Zwecke verwendet.</p>');
        $manager->persist($entry);

        $entry = new KnowledgeBase();
        $entry->setSite('knowledge_base');
        $entry->setCategorie('cannabis_tips');
        $entry->setArticleName('Wie wirkt Cannabis auf den Körper?');
        $entry->setArticleContent('<p>Die Wirkung von Cannabis kann von Person zu Person unterschiedlich sein, hängt jedoch hauptsächlich von der Dosis und der Art des Konsums ab. Zu den häufigsten Effekten gehören Entspannung, veränderte Wahrnehmung, gesteigerter Appetit und Euphorie. Zu den möglichen Nebenwirkungen zählen Angstzustände, Paranoia und ein trockener Mund.</p>');
        $manager->persist($entry);

        $entry = new KnowledgeBase();
        $entry->setSite('knowledge_base');
        $entry->setCategorie('cannabis_tips');
        $entry->setArticleName('Wichtige Stoffe in Cannabis und ihre Wirkung');
        $entry->setArticleContent('
            <p>Cannabis enthält eine Vielzahl von Stoffen, die seine Wirkung beeinflussen können. Hier sind einige der wichtigsten:</p>

            <h4>Cannabinoide</h4>
            <p>Cannabinoide sind die aktiven chemischen Verbindungen in Cannabis, die auf das Endocannabinoid-System des Körpers wirken. Die bekanntesten Cannabinoide sind:</p>
            <ul>
                <li><strong>Tetrahydrocannabinol (THC):</strong> Das psychoaktive Cannabinoid, das für die berauschende Wirkung von Cannabis verantwortlich ist.</li>
                <li><strong>Cannabidiol (CBD):</strong> Ein nicht psychoaktives Cannabinoid, das für seine beruhigenden und entzündungshemmenden Eigenschaften bekannt ist.</li>
                <li><strong>Cannabigerol (CBG):</strong> Ein nicht psychoaktives Cannabinoid, das als Vorläufer für andere Cannabinoide dient und entzündungshemmende und antibakterielle Eigenschaften besitzt.</li>
                <li><strong>Cannabinol (CBN):</strong> Ein leicht psychoaktives Cannabinoid, das durch den Abbau von THC entsteht und für seine beruhigende Wirkung bekannt ist.</li>
                <li><strong>Tetrahydrocannabivarin (THCV):</strong> Ein psychoaktives Cannabinoid, das in höheren Konzentrationen appetithemmende Eigenschaften haben kann.</li>
            </ul>
        
            <h4>Terpene</h4>
            <p>Terpene sind aromatische Verbindungen, die in vielen Pflanzen vorkommen, einschließlich Cannabis. Sie beeinflussen nicht nur den Geruch und Geschmack der Pflanze, sondern auch ihre Wirkung. Einige wichtige Terpene sind:</p>
            <ul>
                <li><strong>Myrcen:</strong> Hat eine beruhigende Wirkung und kann die psychoaktive Wirkung von THC verstärken.</li>
                <li><strong>Limonen:</strong> Bekannt für seine stimmungsaufhellenden und stressreduzierenden Eigenschaften.</li>
                <li><strong>Pinene:</strong> Bekannt für seine entzündungshemmenden und bronchienerweiternden Eigenschaften.</li>
                <li><strong>Linalool:</strong> Bekannt für seine beruhigenden und angstlösenden Eigenschaften.</li>
                <li><strong>Caryophyllen:</strong> Bekannt für seine entzündungshemmenden und schmerzlindernden Eigenschaften.</li>
            </ul>
        
            <h4>Flavonoide</h4>
            <p>Flavonoide sind sekundäre Pflanzenstoffe, die in Cannabis vorkommen und ebenfalls zur Wirkung beitragen können. Die Forschung zu Flavonoiden in Cannabis steht noch am Anfang, aber sie könnten in Zukunft wichtige Erkenntnisse liefern.</p>
        ');
        $manager->persist($entry);

        $entry = new KnowledgeBase();
        $entry->setSite('knowledge_base');
        $entry->setCategorie('cannabis_tips');
        $entry->setArticleName('Welche Konsummethoden gibt es?');
        $entry->setArticleContent('
            <p>Es gibt verschiedene Methoden, Cannabis zu konsumieren, darunter:</p>
            <ul>
                <li><strong>Rauchen:</strong> Cannabis wird in einer Zigarette (Joint) oder einer Pfeife (Bong) geraucht.</li>
                <li><strong>Verdampfen (Vaping):</strong> Cannabis wird erhitzt, bis die Wirkstoffe verdampfen, ohne das Pflanzenmaterial zu verbrennen.</li>
                <li><strong>Esswaren (Edibles):</strong> Cannabis wird in Lebensmitteln oder Getränken verarbeitet.</li>
                <li><strong>Öle und Tinkturen:</strong> Cannabisextrakte werden oral eingenommen oder unter die Zunge geträufelt.</li>
            </ul>
        ');
        $manager->persist($entry);

        $entry = new KnowledgeBase();
        $entry->setSite('knowledge_base');
        $entry->setCategorie('cannabis_tips');
        $entry->setArticleName('Was ist ein Strain?');
        $entry->setArticleContent('<p>Ein Strain ist eine bestimmte Sorte von Cannabis, die sich durch einzigartige Merkmale wie Geruch, Geschmack und Wirkung auszeichnet. Strains werden oft nach ihrem genetischen Ursprung kategorisiert, wie Indica, Sativa oder Hybrid, und können unterschiedliche Mengen an THC und CBD enthalten.</p>');
        $manager->persist($entry);

        $entry = new KnowledgeBase();
        $entry->setSite('knowledge_base');
        $entry->setCategorie('cannabis_tips');
        $entry->setArticleName("Was bedeuten 'indica', 'sativa' und 'ruderalis'?");
        $entry->setArticleContent('
            <p>Indica, Sativa und Ruderalis sind die drei Hauptarten von Cannabis:</p>
            <ul>
              <li><strong>Indica:</strong> Diese Pflanzen sind meist kürzer und buschiger. Sie haben oft einen höheren CBD-Gehalt und werden mit entspannenden, sedierenden Effekten in Verbindung gebracht.</li>
              <li><strong>Sativa:</strong> Diese Pflanzen sind größer und dünner. Sie haben oft einen höheren THC-Gehalt und werden mit belebenden, zerebralen Effekten in Verbindung gebracht.</li>
              <li><strong>Ruderalis:</strong> Diese Pflanzen sind kleiner und robust. Sie werden selten allein verwendet, sondern oft mit Indica oder Sativa gekreuzt, um Autoflowering-Strains zu erzeugen.</li>
            </ul>
        ');
        $manager->persist($entry);

        $entry = new KnowledgeBase();
        $entry->setSite('knowledge_base');
        $entry->setCategorie('growing_tips');
        $entry->setArticleName('Was benötige ich, um Cannabis anzubauen?');
        $entry->setArticleContent('
            <p>Um Cannabis erfolgreich anzubauen, benötigen Sie folgende Grundausstattung:</p>
            <ul>
                <li><strong>Samen oder Setzlinge:</strong> Wählen Sie eine Sorte, die zu Ihrem Klima und Ihren Bedürfnissen passt.</li>
                <li><strong>Beleuchtung:</strong> Für den Innenanbau benötigen Sie spezielle Lampen (z.B. LED, HPS).</li>
                <li><strong>Anbaumedium:</strong> Erde, Kokosfaser oder Hydroponiksysteme.</li>
                <li><strong>Nährstoffe:</strong> Spezielle Düngemittel für die Wachstums- und Blütephase.</li>
                <li><strong>Lüftung:</strong> Ein Belüftungssystem, um die Luftzirkulation und Temperatur zu regulieren.</li>
            </ul>
        ');
        $manager->persist($entry);

        $entry = new KnowledgeBase();
        $entry->setSite('knowledge_base');
        $entry->setCategorie('growing_tips');
        $entry->setArticleName('Wie lange dauert der Anbau von Cannabis?');
        $entry->setArticleContent('<p>Die Anbauzeit von Cannabis variiert je nach Sorte und Anbaumethode. Im Allgemeinen dauert es von der Keimung bis zur Ernte etwa 3 bis 5 Monate. Dies umfasst die Keimungsphase (1-2 Wochen), die Wachstumsphase (4-8 Wochen) und die Blütephase (6-12 Wochen).</p>');
        $manager->persist($entry);

        $entry = new KnowledgeBase();
        $entry->setSite('knowledge_base');
        $entry->setCategorie('growing_tips');
        $entry->setArticleName('Welche Pflege benötigen die Pflanzen?');
        $entry->setArticleContent('
            <p>Um gesunde Cannabispflanzen zu züchten, müssen Sie Folgendes beachten:</p>
            <ul>
              <li><strong>Bewässerung:</strong> Gießen Sie regelmäßig, aber vermeiden Sie Staunässe.</li>
              <li><strong>Nährstoffe:</strong> Düngen Sie die Pflanzen entsprechend ihrem Wachstumsstadium.</li>
              <li><strong>Beleuchtung:</strong> Sorgen Sie für ausreichend Licht, insbesondere während der Wachstumsphase.</li>
              <li><strong>Beschneidung und Training:</strong> Entfernen Sie abgestorbene Blätter und trainieren Sie die Pflanzen, um das Wachstum zu optimieren.</li>
              <li><strong>Schädlingsbekämpfung:</strong> Überwachen Sie die Pflanzen auf Schädlinge und Krankheiten und handeln Sie bei Bedarf schnell.</li>
            </ul>
        ');
        $manager->persist($entry);

        $entry = new KnowledgeBase();
        $entry->setSite('knowledge_base');
        $entry->setCategorie('growing_tips');
        $entry->setArticleName('Was ist ein breeder/Züchter?');
        $entry->setArticleContent('<p>Ein Breeder oder Züchter ist eine Person oder ein Unternehmen, das sich auf die Entwicklung neuer Cannabisstrains spezialisiert hat. Durch die Kreuzung verschiedener Pflanzen können Breeder spezifische Merkmale wie Potenz, Geschmack, Aroma und Wachstumseigenschaften hervorheben.</p>');
        $manager->persist($entry);

        $entry = new KnowledgeBase();
        $entry->setSite('knowledge_base');
        $entry->setCategorie('growing_tips');
        $entry->setArticleName('Was ist ein Seed?');
        $entry->setArticleContent('<p>Ein Seed ist ein Samen, der zur Aufzucht von Cannabispflanzen verwendet wird. Samen können feminisiert sein, was bedeutet, dass sie mit hoher Wahrscheinlichkeit weibliche Pflanzen hervorbringen, die Blüten produzieren. Reguläre Samen können sowohl männliche als auch weibliche Pflanzen hervorbringen.</p>');
        $manager->persist($entry);

        $entry = new KnowledgeBase();
        $entry->setSite('knowledge_base');
        $entry->setCategorie('growing_tips');
        $entry->setArticleName('Was bedeutet (nicht) femisiert?');
        $entry->setArticleContent('<p>Feminisierte Samen sind speziell gezüchtet, um fast ausschließlich weibliche Pflanzen hervorzubringen, die Blüten produzieren. Nicht-feminisierte oder reguläre Samen können sowohl männliche als auch weibliche Pflanzen hervorbringen. Männliche Pflanzen werden in der Regel entfernt, da sie keine Blüten produzieren und die weiblichen Pflanzen bestäuben können.</p>');
        $manager->persist($entry);

        $entry = new KnowledgeBase();
        $entry->setSite('knowledge_base');
        $entry->setCategorie('growing_tips');
        $entry->setArticleName('Was ist ein hybrid?');
        $entry->setArticleContent('<p>Ein Hybrid ist eine Cannabispflanze, die durch Kreuzung von Indica- und Sativa-Pflanzen entstanden ist. Hybride können eine Mischung der Eigenschaften beider Arten haben und bieten oft eine ausgewogene Wirkung, die sowohl körperliche Entspannung als auch zerebrale Stimulation umfassen kann.</p>');
        $manager->persist($entry);

        $entry = new KnowledgeBase();
        $entry->setSite('knowledge_base');
        $entry->setCategorie('growing_tips');
        $entry->setArticleName('Was bedeutet close-only strain?');
        $entry->setArticleContent("<p>Ein 'clone-only strain' ist eine Cannabissorte, die nur durch Klonen und nicht durch Samen vermehrt wird. Das bedeutet, dass die genetische Identität der Pflanze nur durch das Schneiden und Wurzeln eines Teils der Mutterpflanze erhalten bleibt. Dies stellt sicher, dass die spezifischen Merkmale der Sorte konsistent bleiben.</p>");
        $manager->persist($entry);

        $entry = new KnowledgeBase();
        $entry->setSite('knowledge_base');
        $entry->setCategorie('growing_tips');
        $entry->setArticleName('Post-Harvest Prozesse: Ein Leitfaden');
        $entry->setArticleContent('
            <p>Die Post-Harvest-Prozesse sind entscheidend für die Qualität und Potenz von Cannabis. Hier sind die wichtigsten Schritte:</p>

            <h4>1. Ernte</h4>
            <p>Der erste Schritt ist die Ernte der Pflanzen. Achten Sie darauf, die Pflanzen zum richtigen Zeitpunkt zu ernten, um die besten Ergebnisse zu erzielen. Anzeichen für den richtigen Erntezeitpunkt sind:</p>
            <ul>
                <li>Die Trichome sind milchig-weiß und einige sind bernsteinfarben.</li>
                <li>Die Blütenstempel sind größtenteils braun oder rot.</li>
            </ul>

            <h4>2. Trocknen</h4>
            <p>Nach der Ernte müssen die Pflanzen sorgfältig getrocknet werden, um Schimmelbildung zu verhindern und die Potenz zu maximieren. Hier sind die Schritte zum Trocknen:</p>
            <ul>
                <li>Hängen Sie die Pflanzen kopfüber in einem dunklen, gut belüfteten Raum auf.</li>
                <li>Halten Sie die Temperatur zwischen 15-21°C (60-70°F) und die Luftfeuchtigkeit bei 45-55%.</li>
                <li>Die Trocknung dauert normalerweise 7-14 Tage.</li>
            </ul>

            <h4>3. Aushärten</h4>
            <p>Das Aushärten ist ein kritischer Prozess, der die Qualität der Blüten verbessert. So gehts:</p>
            <ul>
                <li>Schneiden Sie die getrockneten Blüten von den Ästen ab und legen Sie sie in luftdichte Glasbehälter.</li>
                <li>Lagern Sie die Behälter in einem kühlen, dunklen Raum.</li>
                <li>Öffnen Sie die Behälter täglich für 10-15 Minuten in den ersten zwei Wochen, um überschüssige Feuchtigkeit entweichen zu lassen (dies wird "Burping" genannt).</li>
                <li>Der Aushärtungsprozess dauert mindestens 2-4 Wochen, kann aber auch mehrere Monate dauern.</li>
            </ul>

            <h4>4. Lagern</h4>
            <p>Die richtige Lagerung ist wichtig, um die Qualität und Potenz der Blüten zu erhalten. Beachten Sie die folgenden Tipps:</p>
            <ul>
                <li>Lagern Sie die Blüten in luftdichten Behältern, vorzugsweise aus Glas.</li>
                <li>Halten Sie die Behälter an einem kühlen, dunklen Ort.</li>
                <li>Vermeiden Sie direkte Sonneneinstrahlung und extreme Temperaturen.</li>
            </ul>
       ');
        $manager->persist($entry);

        $entry = new KnowledgeBase();
        $entry->setSite('knowledge_base');
        $entry->setCategorie('consuming_tips');
        $entry->setArticleName('Welche Risiken sind mit dem Cannabiskonsum verbunden?');
        $entry->setArticleContent('
            <p>Obwohl viele Menschen Cannabis ohne ernsthafte Probleme konsumieren, gibt es potenzielle Risiken:</p>
            <ul>
              <li><strong>Psychische Gesundheit:</strong> Hohe THC-Dosen können Angstzustände, Paranoia und psychotische Episoden auslösen, insbesondere bei gefährdeten Personen.</li>
              <li><strong>Abhängigkeit:</strong> Langfristiger, regelmäßiger Konsum kann zu einer psychischen Abhängigkeit führen.</li>
              <li><strong>Kognitive Funktionen:</strong> Bei Jugendlichen kann der Konsum die Gehirnentwicklung beeinträchtigen und zu langfristigen kognitiven Problemen führen.</li>
              <li><strong>Körperliche Gesundheit:</strong> Rauchen von Cannabis kann die Lungen schädigen, ähnlich wie Tabakrauch.</li>
            </ul>
        ');
        $manager->persist($entry);

        $entry = new KnowledgeBase();
        $entry->setSite('knowledge_base');
        $entry->setCategorie('consuming_tips');
        $entry->setArticleName('Was sollte ich tun, wenn ich zu viel Cannabis konsumiert habe?');
        $entry->setArticleContent('
            <p>Wenn Sie zu viel Cannabis konsumiert haben, können folgende Maßnahmen helfen:</p>
            <ul>
              <li><strong>Bleiben Sie ruhig:</strong> Panik verschlimmert die Situation. Versuchen Sie, ruhig zu bleiben und tief zu atmen.</li>
              <li><strong>Hydrieren:</strong> Trinken Sie Wasser, um einen trockenen Mund zu lindern und Ihren Körper zu unterstützen.</li>
              <li><strong>Essen:</strong> Eine kleine Mahlzeit kann helfen, die Effekte zu mildern.</li>
              <li><strong>Ruhe:</strong> Legen Sie sich an einen ruhigen Ort und warten Sie ab, bis die Wirkung nachlässt. Dies kann mehrere Stunden dauern.</li>
              <li><strong>Freunde kontaktieren:</strong> Wenn Sie sich sehr unwohl fühlen, informieren Sie einen Freund oder ein Familienmitglied.</li>
            </ul>
        ');
        $manager->persist($entry);

        $entry = new KnowledgeBase();
        $entry->setSite('knowledge_base');
        $entry->setCategorie('consuming_tips');
        $entry->setArticleName('Kann ich Cannabis mit anderen Substanzen kombinieren?');
        $entry->setArticleContent('<p>Es ist generell nicht empfehlenswert, Cannabis mit anderen Substanzen zu kombinieren. Mischkonsum kann die Wirkungen und Nebenwirkungen verstärken und unvorhersehbare Reaktionen auslösen. Insbesondere die Kombination mit Alkohol oder anderen psychoaktiven Substanzen kann gefährlich sein.</p>');
        $manager->persist($entry);

        $entry = new KnowledgeBase();
        $entry->setSite('knowledge_base');
        $entry->setCategorie('consuming_tips');
        $entry->setArticleName('Welche gesetzlichen Regelungen gibt es?');
        $entry->setArticleContent('<p>Die gesetzlichen Regelungen für Cannabis variieren stark von Land zu Land und sogar von Region zu Region. Es ist wichtig, sich über die spezifischen Gesetze in Ihrem Gebiet zu informieren. In einigen Ländern ist der Konsum von Cannabis vollständig legalisiert, in anderen ist es nur für medizinische Zwecke erlaubt, und in manchen Ländern ist es komplett verboten.</p>');
        $manager->persist($entry);

        $entry = new KnowledgeBase();
        $entry->setSite('knowledge_base');
        $entry->setCategorie('consuming_tips');
        $entry->setArticleName('Welche Gefahren gibt es?');
        $entry->setArticleContent('
            <p>Die Gefahren des Cannabiskonsums umfassen sowohl physische als auch psychische Aspekte:</p>
            <ul>
              <li><strong>Psychische Gesundheit:</strong> Hohe THC-Dosen können Angstzustände, Paranoia und psychotische Episoden auslösen, insbesondere bei gefährdeten Personen.</li>
              <li><strong>Abhängigkeit:</strong> Langfristiger, regelmäßiger Konsum kann zu einer psychischen Abhängigkeit führen.</li>
              <li><strong>Kognitive Funktionen:</strong> Bei Jugendlichen kann der Konsum die Gehirnentwicklung beeinträchtigen und zu langfristigen kognitiven Problemen führen.</li>
              <li><strong>Körperliche Gesundheit:</strong> Rauchen von Cannabis kann die Lungen schädigen, ähnlich wie Tabakrauch.</li>
            </ul>
        ');
        $manager->persist($entry);

        $entry = new KnowledgeBase();
        $entry->setSite('knowledge_base');
        $entry->setCategorie('CannabisConsumptionComplianceMap');
        $entry->setArticleName('Cannabis Consumption Compliance Map');
        $entry->setArticleContent('This is how you use Cannabis Consumption Compliance Map');
        $manager->persist($entry);

        $entry = new KnowledgeBase();
        $entry->setSite('knowledge_base');
        $entry->setCategorie('CannabisVereinssuche');
        $entry->setArticleName('Cannabis Vereinssuche');
        $entry->setArticleContent('This is how you use Cannabis Vereinssuche');
        $manager->persist($entry);

        $entry = new KnowledgeBase();
        $entry->setSite('knowledge_base');
        $entry->setCategorie('BudBashLocator');
        $entry->setArticleName('Bud Bash Locator');
        $entry->setArticleContent('This is how you use Bud Bash Locator');
        $manager->persist($entry);

        $entry = new KnowledgeBase();
        $entry->setSite('knowledge_base');
        $entry->setCategorie('CannastrainLibrary');
        $entry->setArticleName('Cannastrain Library');
        $entry->setArticleContent('This is how you use Cannastrain Library');
        $manager->persist($entry);

        $entry = new KnowledgeBase();
        $entry->setSite('knowledge_base');
        $entry->setCategorie('CannadoseCalculator');
        $entry->setArticleName('Cannadose Calculator');
        $entry->setArticleContent('This is how you use Cannadose Calculator');
        $manager->persist($entry);

        $entry = new KnowledgeBase();
        $entry->setSite('knowledge_base');
        $entry->setCategorie('GrowMate');
        $entry->setArticleName('Grow Mate');
        $entry->setArticleContent('This is how you use Grow Mate');
        $manager->persist($entry);

        $entry = new KnowledgeBase();
        $entry->setSite('knowledge_base');
        $entry->setCategorie('CannaConsultant');
        $entry->setArticleName('CannaConsultant');
        $entry->setArticleContent('This is how you use CannaConsultant');
        $manager->persist($entry);

        $manager->flush();
    }
}

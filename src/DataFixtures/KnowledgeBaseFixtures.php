<?php

namespace App\DataFixtures;

use App\Entity\KnowledgeBase;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class KnowledgeBaseFixtures extends Fixture implements DependentFixtureInterface
{
    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
        ];
    }

    public function load(ObjectManager $manager): void
    {
        $user = $this->getReference(UserFixtures::USER_REFERENCE_1);

        $entry = new KnowledgeBase();
        $entry->setSite('knowledge_base');
        $entry->setCategorie('cannabis_tips');
        $entry->setArticleName('Was ist Cannabis?');
        $entry->setArticleContent('
            <p>Cannabis ist eine Pflanzengattung, die eine Vielzahl von chemischen Verbindungen enthält und seit Jahrtausenden für medizinische, religiöse und Freizeitzwecke genutzt wird. Hier sind einige grundlegende Informationen über Cannabis:</p>
            <section id="pflanzenbeschreibung" data-nav-title="Pflanzenbeschreibung">
                <h4>Pflanzenbeschreibung</h4>
                <p>Cannabis gehört zur Familie der Cannabaceae und umfasst mehrere Arten und Varietäten. Die bekanntesten Arten sind:</p>
                <ul>
                    <li><strong>Cannabis sativa:</strong> Diese Art ist bekannt für ihre anregende und energetisierende Wirkung. Sie hat in der Regel einen hohen THC-Gehalt und niedrigen CBD-Gehalt.</li>
                    <li><strong>Cannabis indica:</strong> Diese Art wird oft für ihre entspannende und beruhigende Wirkung geschätzt. Sie hat normalerweise einen höheren CBD-Gehalt im Vergleich zu THC.</li>
                    <li><strong>Cannabis ruderalis:</strong> Diese Art hat einen niedrigen THC-Gehalt und wird häufig für die Züchtung von autoflowering Sorten verwendet.</li>
                </ul>
            </section>
            
            <section id="chemische-zusammensetzung" data-nav-title="Chemische Zusammensetzung">
                <h4>Chemische Zusammensetzung</h4>
                <p>Cannabis enthält eine Vielzahl von chemischen Verbindungen, die als Cannabinoide, Terpene und Flavonoide bekannt sind. Diese Verbindungen sind für die verschiedenen Wirkungen der Pflanze verantwortlich:</p>
                <ul>
                    <li><strong>Cannabinoide:</strong> Dies sind die aktiven chemischen Verbindungen, die auf das Endocannabinoid-System des Körpers wirken. Die bekanntesten Cannabinoide sind THC und CBD.</li>
                    <li><strong>Terpene:</strong> Aromatische Verbindungen, die den Geruch und Geschmack der Pflanze beeinflussen und ebenfalls zur Wirkung beitragen.</li>
                    <li><strong>Flavonoide:</strong> Sekundäre Pflanzenstoffe, die in vielen Pflanzen vorkommen und antioxidative Eigenschaften haben.</li>
                </ul>
            </section>
            
            <section id="anwendungsgebiete" data-nav-title="Anwendungsgebiete">
                <h4>Anwendungsgebiete</h4>
                <p>Cannabis wird aus verschiedenen Gründen verwendet, darunter medizinische, freizeitliche und industrielle Anwendungen:</p>
                <ul>
                    <li><strong>Medizinische Anwendungen:</strong> Cannabis wird zur Behandlung von Schmerzen, Angst, Epilepsie, Multiple Sklerose und vielen anderen Erkrankungen eingesetzt.</li>
                    <li><strong>Freizeitliche Nutzung:</strong> Cannabis wird wegen seiner psychoaktiven Wirkung verwendet, die Entspannung und Euphorie hervorruft.</li>
                    <li><strong>Industrielle Anwendungen:</strong> Hanf, eine Varietät von Cannabis, wird zur Herstellung von Textilien, Papier, Bau- und Dämmstoffen, Lebensmitteln und anderen Produkten verwendet.</li>
                </ul>
            </section>
            
            <section id="rechtlicher-status" data-nav-title="Rechtlicher Status">
                <h4>Rechtlicher Status</h4>
                <p>Der rechtliche Status von Cannabis variiert weltweit erheblich:</p>
                <ul>
                    <li><strong>Legalisierung:</strong> In einigen Ländern und US-Bundesstaaten ist Cannabis für medizinische und/oder freizeitliche Nutzung legal.</li>
                    <li><strong>Entkriminalisierung:</strong> Einige Länder haben den Besitz kleiner Mengen entkriminalisiert, was bedeutet, dass es keine strafrechtlichen Folgen hat, aber möglicherweise zivilrechtliche Strafen nach sich zieht.</li>
                    <li><strong>Verbot:</strong> In vielen Ländern ist Cannabis weiterhin vollständig illegal, und der Besitz, Verkauf oder Anbau kann strafrechtlich verfolgt werden.</li>
                </ul>
            </section>
        ');
        $entry->setUser($user);
        $entry->setCreatedAt(new DateTimeImmutable());
        $manager->persist($entry);

        $entry = new KnowledgeBase();
        $entry->setSite('knowledge_base');
        $entry->setCategorie('cannabis_tips');
        $entry->setArticleName('Wie wirkt Cannabis auf den Körper?');
        $entry->setArticleContent('<p>Die Wirkung von Cannabis kann von Person zu Person unterschiedlich sein, hängt jedoch hauptsächlich von der Dosis und der Art des Konsums ab. Zu den häufigsten Effekten gehören Entspannung, veränderte Wahrnehmung, gesteigerter Appetit und Euphorie. Zu den möglichen Nebenwirkungen zählen Angstzustände, Paranoia und ein trockener Mund.</p>');
        $entry->setUser($user);
        $entry->setCreatedAt(new DateTimeImmutable());
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
        $entry->setUser($user);
        $entry->setCreatedAt(new DateTimeImmutable());
        $manager->persist($entry);

        $entry = new KnowledgeBase();
        $entry->setSite('knowledge_base');
        $entry->setCategorie('cannabis_tips');
        $entry->setArticleName('Was ist ein Strain?');
        $entry->setArticleContent('<p>Ein Strain ist eine bestimmte Sorte von Cannabis, die sich durch einzigartige Merkmale wie Geruch, Geschmack und Wirkung auszeichnet. Strains werden oft nach ihrem genetischen Ursprung kategorisiert, wie Indica, Sativa oder Hybrid, und können unterschiedliche Mengen an THC und CBD enthalten.</p>');
        $entry->setUser($user);
        $entry->setCreatedAt(new DateTimeImmutable());
        $manager->persist($entry);

        $entry = new KnowledgeBase();
        $entry->setSite('knowledge_base');
        $entry->setCategorie('cannabis_tips');
        $entry->setArticleName('Wichtige Stoffe in Cannabis und ihre Wirkung');
        $entry->setArticleContent('
            <p>Cannabis enthält eine Vielzahl von Stoffen, die seine Wirkung beeinflussen können. Hier sind einige der wichtigsten:</p>
            <section id="cannabinoide" data-nav-title="Cannabinoide">
                <h4>Cannabinoide</h4>
                <p>Cannabinoide sind die aktiven chemischen Verbindungen in Cannabis, die auf das Endocannabinoid-System des Körpers wirken. Die bekanntesten Cannabinoide sind:</p>
                <ul>
                    <li><strong>Tetrahydrocannabinol (THC):</strong> Das psychoaktive Cannabinoid, das für die berauschende Wirkung von Cannabis verantwortlich ist.</li>
                    <li><strong>Cannabidiol (CBD):</strong> Ein nicht psychoaktives Cannabinoid, das für seine beruhigenden und entzündungshemmenden Eigenschaften bekannt ist.</li>
                    <li><strong>Cannabigerol (CBG):</strong> Ein nicht psychoaktives Cannabinoid, das als Vorläufer für andere Cannabinoide dient und entzündungshemmende und antibakterielle Eigenschaften besitzt.</li>
                    <li><strong>Cannabinol (CBN):</strong> Ein leicht psychoaktives Cannabinoid, das durch den Abbau von THC entsteht und für seine beruhigende Wirkung bekannt ist.</li>
                    <li><strong>Tetrahydrocannabivarin (THCV):</strong> Ein psychoaktives Cannabinoid, das in höheren Konzentrationen appetithemmende Eigenschaften haben kann.</li>
                </ul>
            </section>
        
            <section id="terpene" data-nav-title="Terpene">
                <h4>Terpene</h4>
                <p>Terpene sind aromatische Verbindungen, die in vielen Pflanzen vorkommen, einschließlich Cannabis. Sie beeinflussen nicht nur den Geruch und Geschmack der Pflanze, sondern auch ihre Wirkung. Einige wichtige Terpene sind:</p>
                <ul>
                    <li><strong>Myrcen:</strong> Hat eine beruhigende Wirkung und kann die psychoaktive Wirkung von THC verstärken.</li>
                    <li><strong>Limonen:</strong> Bekannt für seine stimmungsaufhellenden und stressreduzierenden Eigenschaften.</li>
                    <li><strong>Pinene:</strong> Bekannt für seine entzündungshemmenden und bronchienerweiternden Eigenschaften.</li>
                    <li><strong>Linalool:</strong> Bekannt für seine beruhigenden und angstlösenden Eigenschaften.</li>
                    <li><strong>Caryophyllen:</strong> Bekannt für seine entzündungshemmenden und schmerzlindernden Eigenschaften.</li>
                </ul>
            </section>
        
            <section id="flavonoide" data-nav-title="Flavonoide">
                <h4>Flavonoide</h4>
                <p>Flavonoide sind sekundäre Pflanzenstoffe, die in Cannabis vorkommen und ebenfalls zur Wirkung beitragen können. Die Forschung zu Flavonoiden in Cannabis steht noch am Anfang, aber sie könnten in Zukunft wichtige Erkenntnisse liefern.</p>
            </section>
        ');
        $entry->setUser($user);
        $entry->setCreatedAt(new DateTimeImmutable());
        $manager->persist($entry);

        $entry = new KnowledgeBase();
        $entry->setSite('knowledge_base');
        $entry->setCategorie('cannabis_tips');
        $entry->setArticleName('Was sind die medizinischen Anwendungen von Cannabis?');
        $entry->setArticleContent('
            <p>Cannabis hat eine Vielzahl von medizinischen Anwendungen, die in verschiedenen Studien untersucht und dokumentiert wurden. Hier sind einige der wichtigsten medizinischen Anwendungen:</p>
            <section id="schmerzbehandlung" data-nav-title="Schmerzbehandlung">
                <h4>Schmerzbehandlung</h4>
                <p>Cannabis wird häufig zur Behandlung chronischer Schmerzen eingesetzt. Cannabinoide wie THC und CBD wirken auf das Endocannabinoid-System und können Schmerzempfindungen lindern. Anwendungsbereiche umfassen:</p>
                <ul>
                    <li><strong>Arthritis:</strong> Lindert Schmerzen und Entzündungen bei Arthritis-Patienten.</li>
                    <li><strong>Neuropathische Schmerzen:</strong> Wirksam bei der Behandlung von Schmerzen, die durch Nervenschäden verursacht werden.</li>
                    <li><strong>Krebsbedingte Schmerzen:</strong> Hilft bei der Schmerzbewältigung für Krebspatienten, insbesondere bei Schmerzen, die durch Tumore oder Krebstherapien verursacht werden.</li>
                </ul>
            </section>
            
            <section id="angst-und-depression" data-nav-title="Angst und Depression">
                <h4>Angst und Depression</h4>
                <p>Einige Studien haben gezeigt, dass Cannabis helfen kann, Symptome von Angst und Depression zu lindern. Insbesondere CBD hat angstlösende und stimmungsaufhellende Eigenschaften:</p>
                <ul>
                    <li><strong>Angststörungen:</strong> CBD kann die Symptome von generalisierten Angststörungen, sozialen Ängsten und PTSD reduzieren.</li>
                    <li><strong>Depression:</strong> Kann stimmungsaufhellend wirken und depressive Symptome mindern.</li>
                </ul>
            </section>
            
            <section id="epilepsie" data-nav-title="Epilepsie">
                <h4>Epilepsie</h4>
                <p>CBD-reiche Cannabispräparate, wie Epidiolex, haben sich als wirksam bei der Reduzierung von Anfällen bei bestimmten Formen der Epilepsie erwiesen, insbesondere bei Kindern:</p>
                <ul>
                    <li><strong>Dravet-Syndrom:</strong> Eine schwere Form der Epilepsie, die in der Kindheit beginnt.</li>
                    <li><strong>Lennox-Gastaut-Syndrom:</strong> Eine weitere schwere Epilepsieform, die schwer zu behandeln ist.</li>
                </ul>
            </section>
            
            <section id="multiple-sklerose" data-nav-title="Multiple Sklerose">
                <h4>Multiple Sklerose</h4>
                <p>Cannabis kann helfen, die Muskelspastizität und Schmerzen bei Patienten mit Multipler Sklerose (MS) zu reduzieren. THC und CBD wirken auf das Nervensystem und können Spasmen lindern:</p>
                <ul>
                    <li><strong>Muskelspasmen:</strong> Reduziert Häufigkeit und Schwere von Muskelkrämpfen.</li>
                    <li><strong>Schmerzen:</strong> Lindert chronische Schmerzen, die mit MS einhergehen.</li>
                </ul>
            </section>
            
            <section id="übelkeit-und-erbrechen" data-nav-title="Übelkeit und Erbrechen">
                <h4>Übelkeit und Erbrechen</h4>
                <p>Cannabis wird oft zur Behandlung von Übelkeit und Erbrechen eingesetzt, insbesondere bei Patienten, die eine Chemotherapie durchlaufen:</p>
                <ul>
                    <li><strong>Chemotherapie-induzierte Übelkeit:</strong> THC kann Übelkeit und Erbrechen lindern, die durch Chemotherapie verursacht werden.</li>
                    <li><strong>Appetitsteigerung:</strong> Hilft bei der Steigerung des Appetits bei Patienten mit schweren Erkrankungen.</li>
                </ul>
            </section>
        ');
        $entry->setUser($user);
        $entry->setCreatedAt(new DateTimeImmutable());
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
        $entry->setUser($user);
        $entry->setCreatedAt(new DateTimeImmutable());
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
        $entry->setUser($user);
        $entry->setCreatedAt(new DateTimeImmutable());
        $manager->persist($entry);

        $entry = new KnowledgeBase();
        $entry->setSite('knowledge_base');
        $entry->setCategorie('growing_tips');
        $entry->setArticleName('Wie lange dauert der Anbau von Cannabis?');
        $entry->setArticleContent('<p>Die Anbauzeit von Cannabis variiert je nach Sorte und Anbaumethode. Im Allgemeinen dauert es von der Keimung bis zur Ernte etwa 3 bis 5 Monate. Dies umfasst die Keimungsphase (1-2 Wochen), die Wachstumsphase (4-8 Wochen) und die Blütephase (6-12 Wochen).</p>');
        $entry->setUser($user);
        $entry->setCreatedAt(new DateTimeImmutable());
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
        $entry->setUser($user);
        $entry->setCreatedAt(new DateTimeImmutable());
        $manager->persist($entry);

        $entry = new KnowledgeBase();
        $entry->setSite('knowledge_base');
        $entry->setCategorie('growing_tips');
        $entry->setArticleName('Was ist ein breeder/Züchter?');
        $entry->setArticleContent('<p>Ein Breeder oder Züchter ist eine Person oder ein Unternehmen, das sich auf die Entwicklung neuer Cannabisstrains spezialisiert hat. Durch die Kreuzung verschiedener Pflanzen können Breeder spezifische Merkmale wie Potenz, Geschmack, Aroma und Wachstumseigenschaften hervorheben.</p>');
        $entry->setUser($user);
        $entry->setCreatedAt(new DateTimeImmutable());
        $manager->persist($entry);

        $entry = new KnowledgeBase();
        $entry->setSite('knowledge_base');
        $entry->setCategorie('growing_tips');
        $entry->setArticleName('Was ist ein Seed?');
        $entry->setArticleContent('<p>Ein Seed ist ein Samen, der zur Aufzucht von Cannabispflanzen verwendet wird. Samen können feminisiert sein, was bedeutet, dass sie mit hoher Wahrscheinlichkeit weibliche Pflanzen hervorbringen, die Blüten produzieren. Reguläre Samen können sowohl männliche als auch weibliche Pflanzen hervorbringen.</p>');
        $entry->setUser($user);
        $entry->setCreatedAt(new DateTimeImmutable());
        $manager->persist($entry);

        $entry = new KnowledgeBase();
        $entry->setSite('knowledge_base');
        $entry->setCategorie('growing_tips');
        $entry->setArticleName('Was bedeutet (nicht) femisiert?');
        $entry->setArticleContent('<p>Feminisierte Samen sind speziell gezüchtet, um fast ausschließlich weibliche Pflanzen hervorzubringen, die Blüten produzieren. Nicht-feminisierte oder reguläre Samen können sowohl männliche als auch weibliche Pflanzen hervorbringen. Männliche Pflanzen werden in der Regel entfernt, da sie keine Blüten produzieren und die weiblichen Pflanzen bestäuben können.</p>');
        $entry->setUser($user);
        $entry->setCreatedAt(new DateTimeImmutable());
        $manager->persist($entry);

        $entry = new KnowledgeBase();
        $entry->setSite('knowledge_base');
        $entry->setCategorie('growing_tips');
        $entry->setArticleName('Was ist ein hybrid?');
        $entry->setArticleContent('<p>Ein Hybrid ist eine Cannabispflanze, die durch Kreuzung von Indica- und Sativa-Pflanzen entstanden ist. Hybride können eine Mischung der Eigenschaften beider Arten haben und bieten oft eine ausgewogene Wirkung, die sowohl körperliche Entspannung als auch zerebrale Stimulation umfassen kann.</p>');
        $entry->setUser($user);
        $entry->setCreatedAt(new DateTimeImmutable());
        $manager->persist($entry);

        $entry = new KnowledgeBase();
        $entry->setSite('knowledge_base');
        $entry->setCategorie('growing_tips');
        $entry->setArticleName('Was bedeutet close-only strain?');
        $entry->setArticleContent("<p>Ein 'clone-only strain' ist eine Cannabissorte, die nur durch Klonen und nicht durch Samen vermehrt wird. Das bedeutet, dass die genetische Identität der Pflanze nur durch das Schneiden und Wurzeln eines Teils der Mutterpflanze erhalten bleibt. Dies stellt sicher, dass die spezifischen Merkmale der Sorte konsistent bleiben.</p>");
        $entry->setUser($user);
        $entry->setCreatedAt(new DateTimeImmutable());
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
        $entry->setUser($user);
        $entry->setCreatedAt(new DateTimeImmutable());
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
        $entry->setUser($user);
        $entry->setCreatedAt(new DateTimeImmutable());
        $manager->persist($entry);

        $entry = new KnowledgeBase();
        $entry->setSite('knowledge_base');
        $entry->setCategorie('consuming_tips');
        $entry->setArticleName('Kann ich Cannabis mit anderen Substanzen kombinieren?');
        $entry->setArticleContent('<p>Es ist generell nicht empfehlenswert, Cannabis mit anderen Substanzen zu kombinieren. Mischkonsum kann die Wirkungen und Nebenwirkungen verstärken und unvorhersehbare Reaktionen auslösen. Insbesondere die Kombination mit Alkohol oder anderen psychoaktiven Substanzen kann gefährlich sein.</p>');
        $entry->setUser($user);
        $entry->setCreatedAt(new DateTimeImmutable());
        $manager->persist($entry);

        $entry = new KnowledgeBase();
        $entry->setSite('knowledge_base');
        $entry->setCategorie('consuming_tips');
        $entry->setArticleName('Welche gesetzlichen Regelungen gibt es?');
        $entry->setArticleContent('<p>Die gesetzlichen Regelungen für Cannabis variieren stark von Land zu Land und sogar von Region zu Region. Es ist wichtig, sich über die spezifischen Gesetze in Ihrem Gebiet zu informieren. In einigen Ländern ist der Konsum von Cannabis vollständig legalisiert, in anderen ist es nur für medizinische Zwecke erlaubt, und in manchen Ländern ist es komplett verboten.</p>');
        $entry->setUser($user);
        $entry->setCreatedAt(new DateTimeImmutable());
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
        $entry->setUser($user);
        $entry->setCreatedAt(new DateTimeImmutable());
        $manager->persist($entry);

        $entry = new KnowledgeBase();
        $entry->setSite('knowledge_base');
        $entry->setCategorie('CannabisConsumptionComplianceMap');
        $entry->setArticleName('Cannabis Consumption Compliance Map');
        $entry->setArticleContent('This is how you use Cannabis Consumption Compliance Map');
        $entry->setUser($user);
        $entry->setCreatedAt(new DateTimeImmutable());
        $manager->persist($entry);

        $entry = new KnowledgeBase();
        $entry->setSite('knowledge_base');
        $entry->setCategorie('CannabisVereinssuche');
        $entry->setArticleName('Cannabis Vereinssuche');
        $entry->setArticleContent('This is how you use Cannabis Vereinssuche');
        $entry->setUser($user);
        $entry->setCreatedAt(new DateTimeImmutable());
        $manager->persist($entry);

        $entry = new KnowledgeBase();
        $entry->setSite('knowledge_base');
        $entry->setCategorie('BudBashLocator');
        $entry->setArticleName('Bud Bash Locator');
        $entry->setArticleContent('This is how you use Bud Bash Locator');
        $entry->setUser($user);
        $entry->setCreatedAt(new DateTimeImmutable());
        $manager->persist($entry);

        $entry = new KnowledgeBase();
        $entry->setSite('knowledge_base');
        $entry->setCategorie('CannastrainLibrary');
        $entry->setArticleName('Cannastrain Library');
        $entry->setArticleContent('This is how you use Cannastrain Library');
        $entry->setUser($user);
        $entry->setCreatedAt(new DateTimeImmutable());
        $manager->persist($entry);

        $entry = new KnowledgeBase();
        $entry->setSite('knowledge_base');
        $entry->setCategorie('CannadoseCalculator');
        $entry->setArticleName('Cannadose Calculator');
        $entry->setArticleContent('This is how you use Cannadose Calculator');
        $entry->setUser($user);
        $entry->setCreatedAt(new DateTimeImmutable());
        $manager->persist($entry);

        $entry = new KnowledgeBase();
        $entry->setSite('knowledge_base');
        $entry->setCategorie('GrowMate');
        $entry->setArticleName('Grow Mate');
        $entry->setArticleContent('This is how you use Grow Mate');
        $entry->setUser($user);
        $entry->setCreatedAt(new DateTimeImmutable());
        $manager->persist($entry);

        $entry = new KnowledgeBase();
        $entry->setSite('knowledge_base');
        $entry->setCategorie('CannaConsultant');
        $entry->setArticleName('CannaConsultant');
        $entry->setArticleContent('This is how you use CannaConsultant');
        $entry->setUser($user);
        $entry->setCreatedAt(new DateTimeImmutable());
        $manager->persist($entry);

        $manager->flush();
    }
}

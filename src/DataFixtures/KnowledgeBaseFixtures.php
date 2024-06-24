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
        $entry->setArticleContent('
            <p>Cannabis wirkt auf den Körper durch eine Vielzahl von Mechanismen, die hauptsächlich durch das Endocannabinoid-System vermittelt werden. Hier sind die wichtigsten Wirkungsweisen von Cannabis auf den Körper:</p>
            <section id="endocannabinoid-system" data-nav-title="Endocannabinoid-System">
                <h4>Endocannabinoid-System</h4>
                <p>Das Endocannabinoid-System (ECS) spielt eine zentrale Rolle bei der Wirkung von Cannabis auf den Körper. Es besteht aus Rezeptoren, Endocannabinoiden und Enzymen:</p>
                <ul>
                    <li><strong>CB1-Rezeptoren:</strong> Diese Rezeptoren befinden sich hauptsächlich im Gehirn und im zentralen Nervensystem. Sie sind für die psychoaktiven Effekte von THC verantwortlich.</li>
                    <li><strong>CB2-Rezeptoren:</strong> Diese Rezeptoren sind hauptsächlich im Immunsystem und in peripheren Organen zu finden. Sie sind an der Regulation von Entzündungen und Immunreaktionen beteiligt.</li>
                    <li><strong>Endocannabinoide:</strong> Dies sind körpereigene Cannabinoide, die an CB1- und CB2-Rezeptoren binden und verschiedene physiologische Prozesse regulieren.</li>
                    <li><strong>Enzyme:</strong> Enzyme wie FAAH und MAGL bauen Endocannabinoide ab und regulieren deren Konzentrationen im Körper.</li>
                </ul>
            </section>
            
            <section id="psychoaktive-wirkung" data-nav-title="Psychoaktive Wirkung">
                <h4>Psychoaktive Wirkung</h4>
                <p>Die psychoaktiven Effekte von Cannabis werden hauptsächlich durch THC (Tetrahydrocannabinol) verursacht, das an CB1-Rezeptoren im Gehirn bindet:</p>
                <ul>
                    <li><strong>Euphorie und Entspannung:</strong> THC kann ein Gefühl von Euphorie und tiefer Entspannung hervorrufen.</li>
                    <li><strong>Veränderte Wahrnehmung:</strong> Es kann zu veränderter Wahrnehmung von Zeit, Raum und sensorischen Erfahrungen führen.</li>
                    <li><strong>Gedächtnis und Konzentration:</strong> THC kann das Kurzzeitgedächtnis und die Konzentrationsfähigkeit beeinträchtigen.</li>
                </ul>
            </section>
            
            <section id="therapeutische-wirkung" data-nav-title="Therapeutische Wirkung">
                <h4>Therapeutische Wirkung</h4>
                <p>Verschiedene Cannabinoide in Cannabis haben unterschiedliche therapeutische Wirkungen:</p>
                <ul>
                    <li><strong>Schmerzlinderung:</strong> THC und CBD können Schmerzen lindern, indem sie auf das Endocannabinoid-System und andere Schmerzrezeptoren wirken.</li>
                    <li><strong>Entzündungshemmung:</strong> CBD hat entzündungshemmende Eigenschaften und kann bei entzündlichen Erkrankungen helfen.</li>
                    <li><strong>Angst und Stress:</strong> CBD kann angstlösende und stressreduzierende Effekte haben, während THC in niedrigen Dosen auch beruhigend wirken kann.</li>
                    <li><strong>Antiepileptische Wirkung:</strong> CBD ist für seine Fähigkeit bekannt, Anfälle bei bestimmten Epilepsieformen zu reduzieren.</li>
                </ul>
            </section>
            
            <section id="nebenwirkungen" data-nav-title="Nebenwirkungen">
                <h4>Nebenwirkungen</h4>
                <p>Obwohl Cannabis viele positive Wirkungen haben kann, sind auch Nebenwirkungen möglich:</p>
                <ul>
                    <li><strong>Akute Nebenwirkungen:</strong> Dazu gehören Mundtrockenheit, rote Augen, erhöhter Herzschlag und in einigen Fällen Angst oder Paranoia.</li>
                    <li><strong>Langzeitnebenwirkungen:</strong> Bei langfristigem Gebrauch können Probleme wie Gedächtnisstörungen, Abhängigkeit und psychische Gesundheitsprobleme auftreten.</li>
                    <li><strong>Interaktionen mit Medikamenten:</strong> Cannabis kann mit anderen Medikamenten interagieren und deren Wirkung beeinflussen.</li>
                </ul>
            </section>
            
            <section id="stoffwechsel-und-ausscheidung" data-nav-title="Stoffwechsel und Ausscheidung">
                <h4>Stoffwechsel und Ausscheidung</h4>
                <p>Die Metabolisierung von Cannabis erfolgt hauptsächlich in der Leber, wo THC und andere Cannabinoide durch Enzyme abgebaut werden. Die Ausscheidung erfolgt über Urin und Stuhl:</p>
                <ul>
                    <li><strong>Leber:</strong> THC wird hauptsächlich durch das Enzym Cytochrom P450 in seine Metaboliten umgewandelt.</li>
                    <li><strong>Ausscheidung:</strong> Die Metaboliten von THC werden über den Urin und den Stuhl ausgeschieden.</li>
                </ul>
            </section>
        ');
        $entry->setUser($user);
        $entry->setCreatedAt(new DateTimeImmutable());
        $manager->persist($entry);

        $entry = new KnowledgeBase();
        $entry->setSite('knowledge_base');
        $entry->setCategorie('cannabis_tips');
        $entry->setArticleName('Wichtige Stoffe in Cannabis und ihre Wirkung');
        $entry->setArticleContent('
            <p>Cannabis enthält eine Vielzahl von chemischen Verbindungen, die seine Wirkungen auf den Körper beeinflussen. Zu den wichtigsten Stoffen gehören Cannabinoide, Terpene und Flavonoide. Hier sind einige der wichtigsten Stoffe und ihre Wirkungen:</p>

            <section id="cannabinoide" data-nav-title="Cannabinoide">
                <h4>Cannabinoide</h4>
                <p>Cannabinoide sind die aktiven chemischen Verbindungen in Cannabis, die auf das Endocannabinoid-System des Körpers wirken. Die bekanntesten Cannabinoide sind:</p>
                <ul>
                    <li><strong>Tetrahydrocannabinol (THC):</strong> Das psychoaktive Cannabinoid, das für die berauschende Wirkung von Cannabis verantwortlich ist. Es wirkt auf CB1-Rezeptoren im Gehirn und kann Euphorie, Entspannung und veränderte Wahrnehmungen hervorrufen.</li>
                    <li><strong>Cannabidiol (CBD):</strong> Ein nicht psychoaktives Cannabinoid, das für seine beruhigenden, entzündungshemmenden und angstlösenden Eigenschaften bekannt ist. CBD interagiert hauptsächlich mit CB2-Rezeptoren und hat eine breite Palette therapeutischer Anwendungen.</li>
                    <li><strong>Cannabigerol (CBG):</strong> Ein nicht psychoaktives Cannabinoid, das als Vorläufer für andere Cannabinoide dient. CBG hat entzündungshemmende und antibakterielle Eigenschaften und kann bei verschiedenen Erkrankungen hilfreich sein.</li>
                    <li><strong>Cannabinol (CBN):</strong> Ein leicht psychoaktives Cannabinoid, das durch den Abbau von THC entsteht. CBN ist bekannt für seine beruhigende Wirkung und kann bei Schlafproblemen helfen.</li>
                    <li><strong>Tetrahydrocannabivarin (THCV):</strong> Ein psychoaktives Cannabinoid, das in höheren Konzentrationen appetithemmende Eigenschaften haben kann und möglicherweise bei der Behandlung von Adipositas und Diabetes nützlich ist.</li>
                </ul>
            </section>
            
            <section id="terpene" data-nav-title="Terpene">
                <h4>Terpene</h4>
                <p>Terpene sind aromatische Verbindungen, die in vielen Pflanzen vorkommen, einschließlich Cannabis. Sie beeinflussen nicht nur den Geruch und Geschmack der Pflanze, sondern auch ihre Wirkung. Einige wichtige Terpene sind:</p>
                <ul>
                    <li><strong>Myrcen:</strong> Hat eine beruhigende Wirkung und kann die psychoaktive Wirkung von THC verstärken. Es kommt häufig in Indica-Sorten vor.</li>
                    <li><strong>Limonen:</strong> Bekannt für seine stimmungsaufhellenden und stressreduzierenden Eigenschaften. Es kommt auch in Zitrusfrüchten vor und wird oft in Sativa-Sorten gefunden.</li>
                    <li><strong>Pinene:</strong> Bekannt für seine entzündungshemmenden und bronchienerweiternden Eigenschaften. Es kommt auch in Kiefern und anderen Nadelbäumen vor.</li>
                    <li><strong>Linalool:</strong> Bekannt für seine beruhigenden und angstlösenden Eigenschaften. Es kommt auch in Lavendel vor und kann zur Entspannung beitragen.</li>
                    <li><strong>Caryophyllen:</strong> Bekannt für seine entzündungshemmenden und schmerzlindernden Eigenschaften. Es kommt auch in schwarzem Pfeffer vor und bindet an CB2-Rezeptoren.</li>
                </ul>
            </section>
            
            <section id="flavonoide" data-nav-title="Flavonoide">
                <h4>Flavonoide</h4>
                <p>Flavonoide sind sekundäre Pflanzenstoffe, die in Cannabis vorkommen und ebenfalls zur Wirkung beitragen können. Sie sind bekannt für ihre antioxidativen und entzündungshemmenden Eigenschaften. Einige wichtige Flavonoide in Cannabis sind:</p>
                <ul>
                    <li><strong>Quercetin:</strong> Ein Antioxidans, das entzündungshemmende und antivirale Eigenschaften besitzt.</li>
                    <li><strong>Kaempferol:</strong> Hat antioxidative Eigenschaften und kann helfen, das Risiko für Herzkrankheiten und Krebs zu reduzieren.</li>
                    <li><strong>Apigenin:</strong> Hat beruhigende und angstlösende Wirkungen und kann bei der Behandlung von Schlaflosigkeit und Angststörungen hilfreich sein.</li>
                </ul>
            </section>
        ');
        $entry->setUser($user);
        $entry->setCreatedAt(new DateTimeImmutable());
        $manager->persist($entry);

        $entry = new KnowledgeBase();
        $entry->setSite('knowledge_base');
        $entry->setCategorie('cannabis_tips');
        $entry->setArticleName('Welche Konsummethoden gibt es?');
        $entry->setArticleContent('
            <p>Es gibt verschiedene Methoden, Cannabis zu konsumieren, die jeweils unterschiedliche Wirkungen und Risiken mit sich bringen. Hier sind die wichtigsten Konsummethoden:</p>

            <section id="inhalation" data-nav-title="Inhalation">
                <h4>Inhalation</h4>
                <p>Die Inhalation ist eine der gebräuchlichsten Methoden, Cannabis zu konsumieren. Sie ermöglicht eine schnelle Aufnahme der Wirkstoffe durch die Lunge in den Blutkreislauf:</p>
                <ul>
                    <li><strong>Rauchen:</strong> Cannabisblüten oder -konzentrate werden in einem Joint, einer Pfeife oder einem Bong geraucht. Diese Methode führt zu einer schnellen Wirkung, birgt jedoch Risiken für die Atemwege.</li>
                    <li><strong>Verdampfen (Vaping):</strong> Beim Verdampfen wird Cannabis auf eine Temperatur erhitzt, bei der die Wirkstoffe verdampfen, aber nicht verbrannt werden. Dies reduziert die Belastung für die Lunge im Vergleich zum Rauchen.</li>
                </ul>
            </section>
            
            <section id="oraler-konsum" data-nav-title="Oraler Konsum">
                <h4>Oraler Konsum</h4>
                <p>Beim oralen Konsum wird Cannabis durch den Mund aufgenommen, was zu einer langsameren, aber länger anhaltenden Wirkung führt:</p>
                <ul>
                    <li><strong>Esswaren (Edibles):</strong> Cannabis wird in Lebensmittel wie Kekse, Brownies, Gummibärchen oder Getränke eingearbeitet. Die Wirkung tritt langsamer ein (30 Minuten bis 2 Stunden), hält aber länger an (4 bis 8 Stunden).</li>
                    <li><strong>Kapseln und Pillen:</strong> Cannabisöl oder -extrakte werden in Kapseln oder Pillenform eingenommen, was eine präzise Dosierung ermöglicht.</li>
                    <li><strong>Tinkturen:</strong> Alkoholbasierte Extrakte, die unter die Zunge getropft oder zu Speisen und Getränken hinzugefügt werden können. Die Wirkung tritt schneller ein als bei Esswaren, da die Wirkstoffe über die Mundschleimhaut aufgenommen werden.</li>
                </ul>
            </section>
            
            <section id="topische-anwendung" data-nav-title="Topische Anwendung">
                <h4>Topische Anwendung</h4>
                <p>Bei der topischen Anwendung wird Cannabis direkt auf die Haut aufgetragen. Diese Methode wird häufig zur Linderung lokaler Schmerzen und Entzündungen verwendet:</p>
                <ul>
                    <li><strong>Cremes und Salben:</strong> Cannabisinfundierte Cremes, Salben oder Balsame, die direkt auf die Haut aufgetragen werden, um Schmerzen und Entzündungen zu lindern.</li>
                    <li><strong>Transdermale Pflaster:</strong> Pflaster, die eine kontrollierte Freisetzung von Cannabinoiden über die Haut ermöglichen und eine länger anhaltende Wirkung bieten.</li>
                </ul>
            </section>
            
            <section id="sublinguale-anwendung" data-nav-title="Sublinguale Anwendung">
                <h4>Sublinguale Anwendung</h4>
                <p>Bei der sublingualen Anwendung wird Cannabis unter die Zunge gelegt, wo es schnell in den Blutkreislauf aufgenommen wird:</p>
                <ul>
                    <li><strong>Öle und Tinkturen:</strong> Cannabisöle oder -tinkturen, die unter die Zunge getropft werden. Die Wirkung tritt relativ schnell ein (15 bis 45 Minuten).</li>
                    <li><strong>Sublinguale Streifen:</strong> Dünne Streifen, die sich unter der Zunge auflösen und eine schnelle Aufnahme der Wirkstoffe ermöglichen.</li>
                </ul>
            </section>
            
            <section id="rektale-anwendung" data-nav-title="Rektale Anwendung">
                <h4>Rektale Anwendung</h4>
                <p>Die rektale Anwendung wird seltener genutzt, kann aber bei bestimmten medizinischen Bedingungen vorteilhaft sein:</p>
                <ul>
                    <li><strong>Zäpfchen:</strong> Cannabisinfundierte Zäpfchen, die rektal eingeführt werden. Diese Methode kann eine schnelle und effiziente Aufnahme der Wirkstoffe ermöglichen, ohne die psychoaktiven Effekte, die beim Rauchen oder oralen Konsum auftreten können.</li>
                </ul>
            </section>
        ');
        $entry->setUser($user);
        $entry->setCreatedAt(new DateTimeImmutable());
        $manager->persist($entry);

        $entry = new KnowledgeBase();
        $entry->setSite('knowledge_base');
        $entry->setCategorie('cannabis_tips');
        $entry->setArticleName('Was ist ein Strain?');
        $entry->setArticleContent('
            <p>Ein "Strain" (auf Deutsch: Sorte) bezeichnet eine spezifische genetische Varietät von Cannabis. Strains unterscheiden sich in ihrem chemischen Profil, einschließlich der Konzentrationen von Cannabinoiden und Terpenen, was zu unterschiedlichen Wirkungen und Aromen führt. Hier sind einige wesentliche Informationen zu Cannabis-Strains:</p>
    
            <section id="genetik" data-nav-title="Genetik">
                <h4>Genetik</h4>
                <p>Die Genetik eines Strains bestimmt seine Eigenschaften und Wirkungen. Es gibt drei Hauptkategorien von Cannabis-Strains:</p>
                <ul>
                    <li><strong>Indica:</strong> Diese Strains sind in der Regel kürzer und buschiger mit breiten Blättern. Sie sind bekannt für ihre entspannende und sedierende Wirkung, die oft als "körperbetontes High" beschrieben wird.</li>
                    <li><strong>Sativa:</strong> Diese Strains wachsen höher und haben schmalere Blätter. Sie sind bekannt für ihre anregende und euphorisierende Wirkung, die oft als "geistbetontes High" beschrieben wird.</li>
                    <li><strong>Hybrid:</strong> Hybride sind Kreuzungen zwischen Indica- und Sativa-Strains und können Eigenschaften beider Elternteile aufweisen. Sie bieten eine Mischung aus entspannenden und anregenden Wirkungen.</li>
                </ul>
            </section>
            
            <section id="cannabinoid-profil" data-nav-title="Cannabinoid-Profil">
                <h4>Cannabinoid-Profil</h4>
                <p>Das Cannabinoid-Profil eines Strains bezieht sich auf die spezifischen Mengen und Verhältnisse von Cannabinoiden wie THC und CBD:</p>
                <ul>
                    <li><strong>THC-dominant:</strong> Diese Strains enthalten hohe THC-Werte und sind für ihre starke psychoaktive Wirkung bekannt.</li>
                    <li><strong>CBD-dominant:</strong> Diese Strains haben hohe CBD-Werte und niedrige THC-Werte, was sie für medizinische Anwendungen geeignet macht, ohne starke psychoaktive Effekte.</li>
                    <li><strong>Ausgewogen:</strong> Strains mit ausgewogenen THC- und CBD-Werten bieten sowohl therapeutische als auch milde psychoaktive Wirkungen.</li>
                </ul>
            </section>
            
            <section id="terpen-profil" data-nav-title="Terpen-Profil">
                <h4>Terpen-Profil</h4>
                <p>Terpene sind aromatische Verbindungen, die nicht nur den Geruch und Geschmack eines Strains beeinflussen, sondern auch seine Wirkung modifizieren können:</p>
                <ul>
                    <li><strong>Myrcen-reiche Strains:</strong> Diese Strains haben oft eine beruhigende Wirkung und können die psychoaktive Wirkung von THC verstärken.</li>
                    <li><strong>Limonen-reiche Strains:</strong> Bekannt für ihre stimmungsaufhellenden und stressreduzierenden Eigenschaften.</li>
                    <li><strong>Pinene-reiche Strains:</strong> Diese Strains können entzündungshemmende und bronchienerweiternde Wirkungen haben.</li>
                    <li><strong>Linalool-reiche Strains:</strong> Diese haben oft beruhigende und angstlösende Wirkungen.</li>
                    <li><strong>Caryophyllen-reiche Strains:</strong> Diese sind bekannt für ihre entzündungshemmenden und schmerzlindernden Eigenschaften.</li>
                </ul>
            </section>
            
            <section id="anwendungsgebiete" data-nav-title="Anwendungsgebiete">
                <h4>Anwendungsgebiete</h4>
                <p>Verschiedene Strains können für unterschiedliche medizinische und freizeitliche Anwendungen geeignet sein:</p>
                <ul>
                    <li><strong>Medizinische Anwendungen:</strong> Bestimmte Strains werden zur Behandlung von Schmerzen, Angst, Schlafstörungen, Entzündungen und anderen Erkrankungen verwendet.</li>
                    <li><strong>Freizeitliche Nutzung:</strong> Strains werden auch aufgrund ihrer psychoaktiven Wirkungen, wie Entspannung, Euphorie oder kreative Anregung, ausgewählt.</li>
                </ul>
            </section>
        ');
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
            <p>Die Begriffe "Indica", "Sativa" und "Ruderalis" beziehen sich auf verschiedene Arten und Unterarten der Cannabispflanze, die sich in ihrem Aussehen, Wachstumsverhalten und ihren Wirkungen unterscheiden. Hier sind die Hauptmerkmale dieser drei Kategorien:</p>
    
            <section id="indica" data-nav-title="Indica">
                <h4>Indica</h4>
                <p>Cannabis Indica ist bekannt für ihre entspannende und beruhigende Wirkung. Diese Pflanzen sind typischerweise kürzer und buschiger als Sativa-Pflanzen und haben breite Blätter:</p>
                <ul>
                    <li><strong>Wachstumsmerkmale:</strong> Indica-Pflanzen sind kompakt und haben eine kürzere Blütezeit, was sie ideal für den Anbau in kälteren Klimazonen macht.</li>
                    <li><strong>Wirkung:</strong> Indica-Strains sind für ihre körperbetonte Wirkung bekannt, die oft zur Entspannung, Schmerzlinderung und zur Förderung des Schlafs genutzt wird.</li>
                    <li><strong>Medizinische Anwendungen:</strong> Indica-Strains werden häufig zur Behandlung von Schlaflosigkeit, Muskelkrämpfen und chronischen Schmerzen eingesetzt.</li>
                </ul>
            </section>
            
            <section id="sativa" data-nav-title="Sativa">
                <h4>Sativa</h4>
                <p>Cannabis Sativa ist für ihre anregende und euphorisierende Wirkung bekannt. Diese Pflanzen sind größer und haben schmalere Blätter als Indica-Pflanzen:</p>
                <ul>
                    <li><strong>Wachstumsmerkmale:</strong> Sativa-Pflanzen wachsen höher und haben eine längere Blütezeit, was sie besser für den Anbau in wärmeren Klimazonen geeignet macht.</li>
                    <li><strong>Wirkung:</strong> Sativa-Strains sind für ihre geistig anregende Wirkung bekannt, die oft zur Steigerung von Kreativität, Fokus und Energie genutzt wird.</li>
                    <li><strong>Medizinische Anwendungen:</strong> Sativa-Strains werden häufig zur Behandlung von Depression, Angst und chronischer Müdigkeit eingesetzt.</li>
                </ul>
            </section>
            
            <section id="ruderalis" data-nav-title="Ruderalis">
                <h4>Ruderalis</h4>
                <p>Cannabis Ruderalis ist weniger bekannt und wird seltener verwendet als Indica und Sativa. Diese Pflanzen sind klein und robust, mit einer kurzen Wachstums- und Blütezeit:</p>
                <ul>
                    <li><strong>Wachstumsmerkmale:</strong> Ruderalis-Pflanzen sind kompakt und wachsen in harschen Klimazonen. Sie haben eine kurze Lebensdauer und blühen automatisch unabhängig vom Lichtzyklus, was als "autoflowering" bekannt ist.</li>
                    <li><strong>Wirkung:</strong> Ruderalis hat einen niedrigen THC-Gehalt und wird selten wegen ihrer psychoaktiven Wirkungen angebaut. Sie wird oft zur Züchtung von autoflowering Hybriden verwendet.</li>
                    <li><strong>Züchtung:</strong> Ruderalis wird in Kreuzungen mit Indica- und Sativa-Strains verwendet, um autoflowering Eigenschaften zu erzeugen, die den Anbau erleichtern.</li>
                </ul>
            </section>
        ');
        $entry->setUser($user);
        $entry->setCreatedAt(new DateTimeImmutable());
        $manager->persist($entry);

        $entry = new KnowledgeBase();
        $entry->setSite('knowledge_base');
        $entry->setCategorie('growing_tips');
        $entry->setArticleName('Was benötige ich, um Cannabis anzubauen?');
        $entry->setArticleContent('
            <p>Der Anbau von Cannabis erfordert einige grundlegende Materialien und Kenntnisse, um gesunde Pflanzen zu züchten und eine gute Ernte zu erzielen. Hier sind die wesentlichen Dinge, die du für den Anbau von Cannabis benötigst:</p>
    
            <section id="saatgut-oder-klone" data-nav-title="Saatgut oder Klone">
                <h4>Saatgut oder Klone</h4>
                <p>Der erste Schritt beim Anbau von Cannabis besteht darin, qualitativ hochwertiges Saatgut oder Klone zu beschaffen:</p>
                <ul>
                    <li><strong>Saatgut:</strong> Wähle feminisierte Samen, um sicherzustellen, dass deine Pflanzen weiblich sind, da nur weibliche Pflanzen Blüten produzieren. Autoflowering-Samen sind ideal für Anfänger, da sie automatisch blühen, unabhängig vom Lichtzyklus.</li>
                    <li><strong>Klone:</strong> Klone sind Stecklinge von einer Mutterpflanze, die bereits bewurzelt sind. Sie garantieren, dass die Pflanzen die gleichen genetischen Eigenschaften wie die Mutterpflanze haben.</li>
                </ul>
            </section>
            
            <section id="anbaumedium" data-nav-title="Anbaumedium">
                <h4>Anbaumedium</h4>
                <p>Das Anbaumedium ist entscheidend für die Nährstoffversorgung und das Wurzelwachstum der Pflanzen:</p>
                <ul>
                    <li><strong>Erde:</strong> Eine hochwertige, nährstoffreiche Erde ist einfach zu handhaben und ideal für Anfänger.</li>
                    <li><strong>Hydrokultur:</strong> Hierbei wachsen die Pflanzen in einem wasserbasierten Medium. Diese Methode erfordert mehr Erfahrung und spezielle Ausrüstung.</li>
                    <li><strong>Kokosfasern:</strong> Ein beliebtes Anbaumedium, das gute Belüftung und Feuchtigkeitsspeicherung bietet.</li>
                </ul>
            </section>
            
            <section id="beleuchtung" data-nav-title="Beleuchtung">
                <h4>Beleuchtung</h4>
                <p>Beleuchtung ist ein kritischer Faktor für das Wachstum und die Blüte von Cannabispflanzen, insbesondere bei Innenanbau:</p>
                <ul>
                    <li><strong>LED-Lampen:</strong> Energieeffizient und bieten ein vollständiges Lichtspektrum für alle Wachstumsphasen.</li>
                    <li><strong>Hochdruck-Natriumdampflampen (HPS):</strong> Ideal für die Blütephase, da sie ein intensives Licht liefern.</li>
                    <li><strong>Leuchtstofflampen:</strong> Geeignet für die Anzucht und frühe Wachstumsphase.</li>
                </ul>
            </section>
            
            <section id="klima-und-belüftung" data-nav-title="Klima und Belüftung">
                <h4>Klima und Belüftung</h4>
                <p>Ein stabiles Klima und eine gute Belüftung sind entscheidend für das Wohlbefinden der Pflanzen:</p>
                <ul>
                    <li><strong>Ventilatoren:</strong> Sorgen für eine gleichmäßige Luftzirkulation und stärken die Pflanzenstiele.</li>
                    <li><strong>Abluftsysteme:</strong> Entfernen überschüssige Wärme und Feuchtigkeit und sorgen für frische Luftzufuhr.</li>
                    <li><strong>Klimaüberwachung:</strong> Thermometer und Hygrometer helfen, Temperatur und Luftfeuchtigkeit zu überwachen und zu kontrollieren.</li>
                </ul>
            </section>
            
            <section id="wasser-und-nährstoffe" data-nav-title="Wasser und Nährstoffe">
                <h4>Wasser und Nährstoffe</h4>
                <p>Die richtige Bewässerung und Nährstoffversorgung sind entscheidend für ein gesundes Pflanzenwachstum:</p>
                <ul>
                    <li><strong>pH-Wert:</strong> Der pH-Wert des Wassers sollte zwischen 6,0 und 7,0 liegen. Ein pH-Messgerät hilft bei der Überwachung.</li>
                    <li><strong>Nährstoffe:</strong> Verwende spezielle Cannabisdünger, die die notwendigen Makro- und Mikronährstoffe enthalten.</li>
                    <li><strong>Bewässerung:</strong> Stelle sicher, dass die Pflanzen gleichmäßig bewässert werden, aber Staunässe vermieden wird.</li>
                </ul>
            </section>
            
            <section id="pflanzenschutz" data-nav-title="Pflanzenschutz">
                <h4>Pflanzenschutz</h4>
                <p>Schädlinge und Krankheiten können die Ernte beeinträchtigen, daher ist Prävention und Kontrolle wichtig:</p>
                <ul>
                    <li><strong>Schädlingsbekämpfung:</strong> Verwende biologische Schädlingsbekämpfungsmittel und halte die Anbauumgebung sauber.</li>
                    <li><strong>Krankheitsprävention:</strong> Überwache die Pflanzen regelmäßig auf Anzeichen von Krankheiten und entferne betroffene Pflanzenteile sofort.</li>
                    <li><strong>Nützlinge:</strong> Integriere nützliche Insekten wie Marienkäfer zur natürlichen Schädlingsbekämpfung.</li>
                </ul>
            </section>
            
            <section id="ernte-und-trocknung" data-nav-title="Ernte und Trocknung">
                <h4>Ernte und Trocknung</h4>
                <p>Die Ernte und Trocknung sind die letzten Schritte, um die Cannabinoide und Terpene in den Blüten zu bewahren:</p>
                <ul>
                    <li><strong>Ernte:</strong> Die Pflanzen werden geerntet, wenn die Trichome milchig-weiß oder bernsteinfarben sind.</li>
                    <li><strong>Trocknung:</strong> Hänge die Blüten an einem dunklen, gut belüfteten Ort auf, um sie langsam zu trocknen. Dies dauert normalerweise 7-14 Tage.</li>
                    <li><strong>Fermentation:</strong> Lagere die getrockneten Blüten in luftdichten Behältern und öffne diese regelmäßig zur Belüftung. Dies verbessert Geschmack und Potenz.</li>
                </ul>
            </section>
        ');
        $entry->setUser($user);
        $entry->setCreatedAt(new DateTimeImmutable());
        $manager->persist($entry);

        $entry = new KnowledgeBase();
        $entry->setSite('knowledge_base');
        $entry->setCategorie('growing_tips');
        $entry->setArticleName('Wie lange dauert der Anbau von Cannabis?');
        $entry->setArticleContent('
            <p>Der Anbau von Cannabis dauert je nach Sorte, Anbaumethode und Umweltbedingungen unterschiedlich lange. Im Allgemeinen lässt sich der Anbauprozess in verschiedene Phasen unterteilen, die jeweils eine bestimmte Zeit in Anspruch nehmen:</p>
    
            <section id="keimung" data-nav-title="Keimung">
                <h4>Keimung</h4>
                <p>Die Keimung ist die erste Phase im Lebenszyklus der Cannabispflanze, in der die Samen zu keimen beginnen:</p>
                <ul>
                    <li><strong>Dauer:</strong> 2-10 Tage</li>
                    <li><strong>Beschreibung:</strong> Die Samen werden in einem feuchten Umfeld platziert, bis der Keimling aus der Samenschale bricht und eine Wurzel bildet.</li>
                </ul>
            </section>
            
            <section id="sämlingsphase" data-nav-title="Sämlingsphase">
                <h4>Sämlingsphase</h4>
                <p>Nach der Keimung beginnt die Sämlingsphase, in der die Pflanze ihre ersten Blätter entwickelt:</p>
                <ul>
                    <li><strong>Dauer:</strong> 2-3 Wochen</li>
                    <li><strong>Beschreibung:</strong> Die junge Pflanze entwickelt ihre ersten echten Blätter und beginnt, schneller zu wachsen. Diese Phase erfordert viel Licht und eine stabile Umgebung.</li>
                </ul>
            </section>
            
            <section id="wachstumsphase" data-nav-title="Wachstumsphase">
                <h4>Wachstumsphase</h4>
                <p>In der Wachstumsphase konzentriert sich die Pflanze auf die Entwicklung von Stängeln, Blättern und Wurzeln:</p>
                <ul>
                    <li><strong>Dauer:</strong> 4-8 Wochen (je nach Sorte und Anbaubedingungen)</li>
                    <li><strong>Beschreibung:</strong> Die Pflanze wächst in die Höhe und Breite und entwickelt ein starkes Wurzelsystem. Diese Phase erfordert intensive Beleuchtung (18-24 Stunden Licht pro Tag) und regelmäßige Nährstoffzufuhr.</li>
                </ul>
            </section>
            
            <section id="blütephase" data-nav-title="Blütephase">
                <h4>Blütephase</h4>
                <p>Die Blütephase ist die letzte Phase, in der die Pflanze Blüten (auch Buds genannt) produziert:</p>
                <ul>
                    <li><strong>Dauer:</strong> 6-12 Wochen (abhängig von der Sorte)</li>
                    <li><strong>Beschreibung:</strong> Die Beleuchtung wird auf einen 12-Stunden-Lichtzyklus umgestellt, um die Blütenbildung zu stimulieren. Die Pflanze entwickelt Harzdrüsen, die Cannabinoide und Terpene enthalten.</li>
                </ul>
            </section>
            
            <section id="ernte-und-nachbehandlung" data-nav-title="Ernte und Nachbehandlung">
                <h4>Ernte und Nachbehandlung</h4>
                <p>Nach der Blütephase erfolgt die Ernte und die Nachbehandlung der Blüten:</p>
                <ul>
                    <li><strong>Ernte:</strong> Die Pflanzen werden geerntet, wenn die Trichome den gewünschten Reifegrad erreicht haben. Dies erkennt man an der Farbe der Trichome (milchig-weiß oder bernsteinfarben).</li>
                    <li><strong>Trocknung:</strong> 1-2 Wochen – Die geernteten Blüten werden an einem dunklen, gut belüfteten Ort aufgehängt, um sie langsam zu trocknen.</li>
                    <li><strong>Fermentation:</strong> 2-4 Wochen oder länger – Die getrockneten Blüten werden in luftdichten Behältern gelagert und regelmäßig belüftet, um Geschmack und Potenz zu verbessern.</li>
                </ul>
            </section>
            
            <section id="gesamtdauer" data-nav-title="Gesamtdauer">
                <h4>Gesamtdauer</h4>
                <p>Die gesamte Anbaudauer von der Keimung bis zur fertigen Ernte beträgt in der Regel etwa 3-5 Monate, abhängig von der Sorte und den Anbaubedingungen:</p>
                <ul>
                    <li><strong>Schnell wachsende Sorten:</strong> Autoflowering-Sorten können innerhalb von 8-10 Wochen nach der Keimung geerntet werden.</li>
                    <li><strong>Langsam wachsende Sorten:</strong> Einige Sativa-dominierte Sorten können bis zu 6 Monate für eine vollständige Entwicklung benötigen.</li>
                </ul>
            </section>
        ');
        $entry->setUser($user);
        $entry->setCreatedAt(new DateTimeImmutable());
        $manager->persist($entry);

        $entry = new KnowledgeBase();
        $entry->setSite('knowledge_base');
        $entry->setCategorie('growing_tips');
        $entry->setArticleName('Welche Pflege benötigen die Pflanzen?');
        $entry->setArticleContent('
            <p>Die Dauer des Cannabis-Anbaus variiert je nach Sorte, Anbaumethode und Umweltbedingungen. Im Allgemeinen lässt sich der Anbauprozess in verschiedene Phasen unterteilen, die jeweils eine bestimmte Zeit in Anspruch nehmen:</p>
    
            <section id="keimung" data-nav-title="Keimung">
                <h4>Keimung</h4>
                <p>Die Keimung ist die erste Phase im Lebenszyklus der Cannabispflanze, in der die Samen zu keimen beginnen:</p>
                <ul>
                    <li><strong>Dauer:</strong> 2-10 Tage</li>
                    <li><strong>Beschreibung:</strong> Die Samen werden in einem feuchten Umfeld platziert, bis der Keimling aus der Samenschale bricht und eine Wurzel bildet.</li>
                </ul>
            </section>
            
            <section id="sämlingsphase" data-nav-title="Sämlingsphase">
                <h4>Sämlingsphase</h4>
                <p>Nach der Keimung beginnt die Sämlingsphase, in der die Pflanze ihre ersten Blätter entwickelt:</p>
                <ul>
                    <li><strong>Dauer:</strong> 2-3 Wochen</li>
                    <li><strong>Beschreibung:</strong> Die junge Pflanze entwickelt ihre ersten echten Blätter und beginnt, schneller zu wachsen. Diese Phase erfordert viel Licht und eine stabile Umgebung.</li>
                </ul>
            </section>
            
            <section id="wachstumsphase" data-nav-title="Wachstumsphase">
                <h4>Wachstumsphase</h4>
                <p>In der Wachstumsphase konzentriert sich die Pflanze auf die Entwicklung von Stängeln, Blättern und Wurzeln:</p>
                <ul>
                    <li><strong>Dauer:</strong> 4-8 Wochen (je nach Sorte und Anbaubedingungen)</li>
                    <li><strong>Beschreibung:</strong> Die Pflanze wächst in die Höhe und Breite und entwickelt ein starkes Wurzelsystem. Diese Phase erfordert intensive Beleuchtung (18-24 Stunden Licht pro Tag) und regelmäßige Nährstoffzufuhr.</li>
                </ul>
            </section>
            
            <section id="blütephase" data-nav-title="Blütephase">
                <h4>Blütephase</h4>
                <p>Die Blütephase ist die letzte Phase, in der die Pflanze Blüten (auch Buds genannt) produziert:</p>
                <ul>
                    <li><strong>Dauer:</strong> 6-12 Wochen (abhängig von der Sorte)</li>
                    <li><strong>Beschreibung:</strong> Die Beleuchtung wird auf einen 12-Stunden-Lichtzyklus umgestellt, um die Blütenbildung zu stimulieren. Die Pflanze entwickelt Harzdrüsen, die Cannabinoide und Terpene enthalten.</li>
                </ul>
            </section>
            
            <section id="ernte-und-nachbehandlung" data-nav-title="Ernte und Nachbehandlung">
                <h4>Ernte und Nachbehandlung</h4>
                <p>Nach der Blütephase erfolgt die Ernte und die Nachbehandlung der Blüten:</p>
                <ul>
                    <li><strong>Ernte:</strong> Die Pflanzen werden geerntet, wenn die Trichome den gewünschten Reifegrad erreicht haben. Dies erkennt man an der Farbe der Trichome (milchig-weiß oder bernsteinfarben).</li>
                    <li><strong>Trocknung:</strong> 1-2 Wochen – Die geernteten Blüten werden an einem dunklen, gut belüfteten Ort aufgehängt, um sie langsam zu trocknen.</li>
                    <li><strong>Fermentation:</strong> 2-4 Wochen oder länger – Die getrockneten Blüten werden in luftdichten Behältern gelagert und regelmäßig belüftet, um Geschmack und Potenz zu verbessern.</li>
                </ul>
            </section>
            
            <section id="gesamtdauer" data-nav-title="Gesamtdauer">
                <h4>Gesamtdauer</h4>
                <p>Die gesamte Anbaudauer von der Keimung bis zur fertigen Ernte beträgt in der Regel etwa 3-5 Monate, abhängig von der Sorte und den Anbaubedingungen:</p>
                <ul>
                    <li><strong>Schnell wachsende Sorten:</strong> Autoflowering-Sorten können innerhalb von 8-10 Wochen nach der Keimung geerntet werden.</li>
                    <li><strong>Langsam wachsende Sorten:</strong> Einige Sativa-dominierte Sorten können bis zu 6 Monate für eine vollständige Entwicklung benötigen.</li>
                </ul>
            </section>
        ');
        $entry->setUser($user);
        $entry->setCreatedAt(new DateTimeImmutable());
        $manager->persist($entry);

        $entry = new KnowledgeBase();
        $entry->setSite('knowledge_base');
        $entry->setCategorie('growing_tips');
        $entry->setArticleName('Was ist ein breeder/Züchter?');
        $entry->setArticleContent('
            <p>Ein Breeder oder Züchter ist eine Person oder ein Unternehmen, das sich auf die Kreuzung und Entwicklung neuer Cannabis-Sorten spezialisiert hat. Diese Züchter arbeiten daran, spezifische Eigenschaften in den Pflanzen zu stabilisieren, wie z.B. hohe Erträge, besondere Aromen, spezielle Wirkungen oder Widerstandsfähigkeit gegen Schädlinge und Krankheiten. Hier sind einige wesentliche Informationen über Breeder und ihre Arbeit:</p>
    
            <section id="aufgaben" data-nav-title="Aufgaben">
                <h4>Aufgaben</h4>
                <p>Die Hauptaufgaben eines Breeders umfassen:</p>
                <ul>
                    <li><strong>Selektion:</strong> Auswahl der besten Pflanzen aus einer Gruppe basierend auf gewünschten Merkmalen wie Potenz, Aroma, Wachstumsmuster und Krankheitsresistenz.</li>
                    <li><strong>Kreuzung:</strong> Paarung von ausgewählten Pflanzen, um Samen mit kombinierten Eigenschaften beider Elternpflanzen zu erzeugen.</li>
                    <li><strong>Stabilisierung:</strong> Mehrfache Rückkreuzungen und Selektionen, um die gewünschten Eigenschaften in den folgenden Generationen zu festigen und stabile Samen zu produzieren.</li>
                    <li><strong>Phänotypen-Analyse:</strong> Untersuchung und Dokumentation der verschiedenen Ausprägungen (Phänotypen) der Nachkommenschaft, um die besten Individuen für die Weiterzucht auszuwählen.</li>
                    <li><strong>Erhalt der Genetik:</strong> Erhaltung und Pflege von Mutterpflanzen und Genetiken, die wertvolle Eigenschaften besitzen.</li>
                </ul>
            </section>
            
            <section id="methoden" data-nav-title="Methoden">
                <h4>Methoden</h4>
                <p>Breeder verwenden verschiedene Methoden zur Entwicklung neuer Cannabis-Sorten:</p>
                <ul>
                    <li><strong>Kreuzung:</strong> Traditionelle Methode, bei der männliche Pollen auf weibliche Blüten übertragen werden, um Samen zu erzeugen.</li>
                    <li><strong>Rückkreuzung:</strong> Kreuzung der Nachkommen mit einem der Elternteile, um bestimmte Eigenschaften zu verstärken.</li>
                    <li><strong>Selbstbestäubung (S1):</strong> Bestäubung einer weiblichen Pflanze mit ihrem eigenen Pollen, oft durch die Induktion von Pollen bei einer weiblichen Pflanze mittels Chemikalien wie Kolloidalem Silber oder STS (Silberthiosulfat).</li>
                    <li><strong>Polyhybriden:</strong> Kreuzung von Pflanzen mit mehreren genetischen Hintergründen, um eine größere genetische Vielfalt und einzigartige Kombinationen von Eigenschaften zu erzeugen.</li>
                </ul>
            </section>
            
            <section id="bedeutung" data-nav-title="Bedeutung">
                <h4>Bedeutung</h4>
                <p>Breeder spielen eine entscheidende Rolle in der Cannabisindustrie, indem sie neue Sorten entwickeln, die den unterschiedlichen Bedürfnissen und Vorlieben der Konsumenten entsprechen:</p>
                <ul>
                    <li><strong>Medizinische Anwendungen:</strong> Entwicklung von Sorten mit hohen CBD-Werten und spezifischen Cannabinoid- und Terpen-Profilen für die Behandlung verschiedener medizinischer Zustände.</li>
                    <li><strong>Freizeitkonsum:</strong> Schaffung von Sorten mit einzigartigen Aromen, Geschmäckern und Wirkungen für den Freizeitgebrauch.</li>
                    <li><strong>Industrielle Nutzung:</strong> Züchtung von Hanfsorten mit hohem Fasergehalt oder hohen Erträgen an Samen und Öl für industrielle Anwendungen.</li>
                </ul>
            </section>
            
            <section id="bekannte-breeder" data-nav-title="Bekannte Breeder">
                <h4>Bekannte Breeder</h4>
                <p>Einige bekannte Breeder und Züchter, die bedeutende Beiträge zur Cannabisgenetik geleistet haben, sind:</p>
                <ul>
                    <li><strong>DJ Short:</strong> Berühmt für die Entwicklung der Blueberry-Sorte.</li>
                    <li><strong>Soma:</strong> Gründer von Soma Seeds, bekannt für die Sorte Amnesia Haze.</li>
                    <li><strong>Shantibaba:</strong> Mitbegründer von Mr. Nice Seedbank, bekannt für Sorten wie White Widow und Super Silver Haze.</li>
                    <li><strong>Arjan Roskam:</strong> Gründer von Green House Seed Company, bekannt für die Entwicklung von Sorten wie Super Lemon Haze und White Rhino.</li>
                </ul>
            </section>
        ');
        $entry->setUser($user);
        $entry->setCreatedAt(new DateTimeImmutable());
        $manager->persist($entry);

        $entry = new KnowledgeBase();
        $entry->setSite('knowledge_base');
        $entry->setCategorie('growing_tips');
        $entry->setArticleName('Was ist ein Seed?');
        $entry->setArticleContent('
            <p>Ein "Seed" (auf Deutsch: Samen) ist die reproduktive Einheit der Cannabispflanze, die alle genetischen Informationen zur Entwicklung einer neuen Pflanze enthält. Hier sind einige grundlegende Informationen über Cannabissamen:</p>
    
            <section id="definition" data-nav-title="Definition">
                <h4>Definition</h4>
                <p>Ein Cannabissamen ist das Ergebnis der Befruchtung einer weiblichen Cannabispflanze durch Pollen einer männlichen Pflanze. Der Samen enthält das genetische Material beider Elternpflanzen und kann zu einer neuen Cannabispflanze heranwachsen.</p>
            </section>
            
            <section id="arten-von-samen" data-nav-title="Arten von Samen">
                <h4>Arten von Samen</h4>
                <p>Es gibt verschiedene Arten von Cannabissamen, die für unterschiedliche Anbauzwecke verwendet werden:</p>
                <ul>
                    <li><strong>Reguläre Samen:</strong> Diese Samen können sowohl männliche als auch weibliche Pflanzen hervorbringen. Sie sind ideal für Züchter, die neue Sorten entwickeln möchten.</li>
                    <li><strong>Feminisierte Samen:</strong> Diese Samen wurden so gezüchtet, dass sie fast ausschließlich weibliche Pflanzen produzieren, was die Ernte von Blüten vereinfacht, da nur weibliche Pflanzen psychoaktive Blüten produzieren.</li>
                    <li><strong>Autoflowering Samen:</strong> Diese Samen produzieren Pflanzen, die automatisch blühen, unabhängig vom Lichtzyklus. Sie sind besonders beliebt bei Anfängern und in Regionen mit kurzen Sommern.</li>
                </ul>
            </section>
            
            <section id="vorteile-von-feminisierten-samen" data-nav-title="Vorteile von feminisierten Samen">
                <h4>Vorteile von feminisierten Samen</h4>
                <p>Feminisierte Samen bieten mehrere Vorteile für den Anbau von Cannabis:</p>
                <ul>
                    <li><strong>Keine männlichen Pflanzen:</strong> Da feminisierte Samen fast ausschließlich weibliche Pflanzen produzieren, müssen keine männlichen Pflanzen entfernt werden, die keine Blüten produzieren und die weiblichen Pflanzen bestäuben könnten.</li>
                    <li><strong>Effizienter Anbau:</strong> Da nur weibliche Pflanzen wachsen, wird der Platz und die Ressourcen im Anbauraum effizienter genutzt.</li>
                    <li><strong>Einfachere Zucht:</strong> Feminisierte Samen sind ideal für Anbauer, die sich auf die Produktion von Blüten konzentrieren möchten, ohne sich um die Selektion von Pflanzen kümmern zu müssen.</li>
                </ul>
            </section>
            
            <section id="keimung-von-samen" data-nav-title="Keimung von Samen">
                <h4>Keimung von Samen</h4>
                <p>Die Keimung ist der erste Schritt im Anbauprozess, bei dem der Samen zu einem Keimling wird. Hier sind die grundlegenden Schritte zur Keimung von Cannabissamen:</p>
                <ul>
                    <li><strong>Einweichen:</strong> Die Samen werden 12-24 Stunden in Wasser eingeweicht, um die Schale aufzuweichen und den Keimungsprozess zu starten.</li>
                    <li><strong>Feuchte Umgebung:</strong> Nach dem Einweichen werden die Samen in ein feuchtes Papiertuch oder direkt in feuchte Erde gelegt. Sie sollten warm (ca. 20-25°C) und dunkel gehalten werden.</li>
                    <li><strong>Keimung:</strong> Innerhalb von 2-10 Tagen bricht der Keimling aus der Samenschale und bildet eine Wurzel. Sobald der Keimling sichtbar ist, kann er in ein Anbaumedium gepflanzt werden.</li>
                </ul>
            </section>
            
            <section id="lagerung-von-samen" data-nav-title="Lagerung von Samen">
                <h4>Lagerung von Samen</h4>
                <p>Die richtige Lagerung von Cannabissamen ist entscheidend, um ihre Keimfähigkeit zu erhalten:</p>
                <ul>
                    <li><strong>Kühle Temperaturen:</strong> Samen sollten an einem kühlen Ort gelagert werden, idealerweise bei Temperaturen zwischen 4-8°C.</li>
                    <li><strong>Trockene Umgebung:</strong> Samen sollten in einer trockenen Umgebung gelagert werden, um Schimmel und Feuchtigkeitsschäden zu vermeiden.</li>
                    <li><strong>Dunkelheit:</strong> Samen sollten vor Licht geschützt gelagert werden, um die Keimfähigkeit zu erhalten. Luftdichte Behälter wie Glasgefäße oder spezielle Samenlagerungsbehälter sind ideal.</li>
                </ul>
            </section>
        ');
        $entry->setUser($user);
        $entry->setCreatedAt(new DateTimeImmutable());
        $manager->persist($entry);

        $entry = new KnowledgeBase();
        $entry->setSite('knowledge_base');
        $entry->setCategorie('growing_tips');
        $entry->setArticleName('Was bedeutet (nicht) femisiert?');
        $entry->setArticleContent('
            <p>Die Begriffe "femisiert" und "nicht femisiert" beziehen sich auf die Art von Cannabissamen und ob sie dazu gezüchtet wurden, fast ausschließlich weibliche Pflanzen zu produzieren. Hier sind die Unterschiede und Bedeutungen:</p>
    
            <section id="femisiert" data-nav-title="Femisiert">
                <h4>Femisiert</h4>
                <p>Feminisierte Samen sind so gezüchtet, dass sie nahezu ausschließlich weibliche Pflanzen produzieren. Weibliche Pflanzen sind wichtig, weil sie die begehrten Blüten (Buds) produzieren, die reich an Cannabinoiden wie THC und CBD sind. Die Hauptmerkmale von feminisierten Samen sind:</p>
                <ul>
                    <li><strong>Geschlecht:</strong> Fast alle Pflanzen, die aus feminisierten Samen wachsen, sind weiblich.</li>
                    <li><strong>Effizienz:</strong> Anbauer müssen keine männlichen Pflanzen identifizieren und entfernen, was Zeit und Ressourcen spart.</li>
                    <li><strong>Ertrag:</strong> Da nur weibliche Pflanzen wachsen, kann der gesamte Anbauraum für die Produktion von Blüten genutzt werden.</li>
                </ul>
            </section>
            
            <section id="nicht-femisiert" data-nav-title="Nicht femisiert">
                <h4>Nicht femisiert</h4>
                <p>Nicht-feminisierte (auch reguläre) Samen produzieren sowohl männliche als auch weibliche Pflanzen. Diese Samen sind nicht speziell behandelt oder gezüchtet, um ausschließlich weibliche Pflanzen zu erzeugen. Die Hauptmerkmale von nicht-feminisierte Samen sind:</p>
                <ul>
                    <li><strong>Geschlecht:</strong> Etwa die Hälfte der Pflanzen aus regulären Samen wird männlich, die andere Hälfte weiblich sein.</li>
                    <li><strong>Zucht:</strong> Reguläre Samen sind ideal für Züchter, die neue Sorten entwickeln möchten, da sowohl männliche als auch weibliche Pflanzen für die Kreuzung zur Verfügung stehen.</li>
                    <li><strong>Aufwand:</strong> Anbauer müssen männliche Pflanzen frühzeitig identifizieren und entfernen, um eine Bestäubung der weiblichen Pflanzen zu verhindern, was zusätzlichen Aufwand bedeutet.</li>
                </ul>
            </section>
            
            <section id="vor-und-nachteile" data-nav-title="Vor- und Nachteile">
                <h4>Vor- und Nachteile</h4>
                <p>Die Wahl zwischen feminisierten und nicht-feminisierte Samen hängt von den Zielen und Präferenzen des Anbauers ab. Hier sind einige Vor- und Nachteile beider Optionen:</p>
                <ul>
                    <li><strong>Feminisierte Samen:</strong></li>
                    <ul>
                        <li><strong>Vorteile:</strong> Höhere Effizienz, keine Notwendigkeit, männliche Pflanzen zu entfernen, maximaler Ertrag.</li>
                        <li><strong>Nachteile:</strong> Keine Möglichkeit zur Erzeugung eigener Samen, höherer Preis.</li>
                    </ul>
                    <li><strong>Nicht-feminisierte Samen:</strong></li>
                    <ul>
                        <li><strong>Vorteile:</strong> Geeignet für Züchtung und Kreuzung, preisgünstiger.</li>
                        <li><strong>Nachteile:</strong> Erfordert Identifizierung und Entfernung männlicher Pflanzen, weniger effizient für reine Blütenproduktion.</li>
                    </ul>
                </ul>
            </section>
            
            <section id="anwendungsgebiete" data-nav-title="Anwendungsgebiete">
                <h4>Anwendungsgebiete</h4>
                <p>Die Wahl zwischen feminisierten und nicht-feminisierten Samen kann je nach Anwendungsgebiet variieren:</p>
                <ul>
                    <li><strong>Heimanbau:</strong> Feminisierte Samen sind ideal für Hobbygärtner, die keine Zeit und Ressourcen in die Identifizierung und Entfernung männlicher Pflanzen investieren möchten.</li>
                    <li><strong>Kommerzielle Produktion:</strong> Feminisierte Samen sind vorteilhaft für kommerzielle Züchter, die eine maximale Blütenproduktion anstreben.</li>
                    <li><strong>Züchtung:</strong> Nicht-feminisierte Samen sind bevorzugt für Züchter, die neue Sorten entwickeln und die genetische Vielfalt nutzen möchten.</li>
                </ul>
            </section>
        ');
        $entry->setUser($user);
        $entry->setCreatedAt(new DateTimeImmutable());
        $manager->persist($entry);

        $entry = new KnowledgeBase();
        $entry->setSite('knowledge_base');
        $entry->setCategorie('growing_tips');
        $entry->setArticleName('Was ist ein hybrid?');
        $entry->setArticleContent('
            <p>Ein Hybrid ist eine Cannabissorte, die durch die Kreuzung von zwei oder mehr verschiedenen Elternsorten gezüchtet wurde. Hybride kombinieren Eigenschaften von Cannabis Indica, Sativa und manchmal Ruderalis, um spezifische Merkmale zu erreichen. Hier sind einige grundlegende Informationen über Hybride:</p>
    
            <section id="definition" data-nav-title="Definition">
                <h4>Definition</h4>
                <p>Ein Hybrid entsteht, wenn zwei verschiedene Cannabissorten gekreuzt werden, um eine neue Sorte zu schaffen, die die besten Eigenschaften beider Elternpflanzen vereint. Hybride können unterschiedlich dominiert sein, je nachdem, welche Eigenschaften stärker ausgeprägt sind:</p>
                <ul>
                    <li><strong>Indica-dominant:</strong> Diese Hybride haben überwiegend Indica-Eigenschaften, was zu einer entspannenden Wirkung führt.</li>
                    <li><strong>Sativa-dominant:</strong> Diese Hybride haben überwiegend Sativa-Eigenschaften, was zu einer anregenden und euphorischen Wirkung führt.</li>
                    <li><strong>Ausgewogen:</strong> Diese Hybride haben eine ausgewogene Mischung aus Indica- und Sativa-Eigenschaften.</li>
                </ul>
            </section>
            
            <section id="vorteile-von-hybriden" data-nav-title="Vorteile von Hybriden">
                <h4>Vorteile von Hybriden</h4>
                <p>Hybride bieten mehrere Vorteile, die sie zu einer beliebten Wahl für Anbauer und Konsumenten machen:</p>
                <ul>
                    <li><strong>Vielseitigkeit:</strong> Hybride können auf spezifische Bedürfnisse und Vorlieben zugeschnitten werden, indem sie Eigenschaften von Indica und Sativa kombinieren.</li>
                    <li><strong>Optimierte Eigenschaften:</strong> Durch die Kreuzung können Züchter Sorten entwickeln, die besondere Merkmale wie hohen Ertrag, kurze Blütezeit oder spezielle Aromen aufweisen.</li>
                    <li><strong>Vielfalt:</strong> Die Vielfalt der Hybriden ermöglicht es Konsumenten, Sorten mit einer breiten Palette von Wirkungen und Geschmacksprofilen zu finden.</li>
                </ul>
            </section>
            
            <section id="beliebte-hybriden" data-nav-title="Beliebte Hybriden">
                <h4>Beliebte Hybriden</h4>
                <p>Es gibt viele beliebte Hybridsorten, die wegen ihrer einzigartigen Eigenschaften geschätzt werden:</p>
                <ul>
                    <li><strong>Girl Scout Cookies (GSC):</strong> Ein bekannter Hybrid, der für seine starke, euphorische Wirkung und sein süßes, erdiges Aroma bekannt ist. GSC ist eine Kreuzung aus OG Kush und Durban Poison.</li>
                    <li><strong>Blue Dream:</strong> Ein Sativa-dominanter Hybrid, der für seine ausgewogene Wirkung und sein süßes, beeriges Aroma bekannt ist. Blue Dream ist eine Kreuzung aus Blueberry und Haze.</li>
                    <li><strong>OG Kush:</strong> Ein Indica-dominanter Hybrid, der für seine entspannende Wirkung und sein erdiges, zitroniges Aroma bekannt ist. OG Kush hat eine komplexe genetische Herkunft, die verschiedene Landrassen umfasst.</li>
                </ul>
            </section>
            
            <section id="anbau-von-hybriden" data-nav-title="Anbau von Hybriden">
                <h4>Anbau von Hybriden</h4>
                <p>Der Anbau von Hybriden kann Vorteile bieten, da sie oft robuster und widerstandsfähiger gegen Umweltbedingungen sind:</p>
                <ul>
                    <li><strong>Anpassungsfähigkeit:</strong> Hybride können besser an verschiedene Anbaubedingungen angepasst werden, sei es drinnen oder draußen.</li>
                    <li><strong>Resistenz:</strong> Viele Hybride sind resistenter gegen Schädlinge, Krankheiten und widrige Wetterbedingungen.</li>
                    <li><strong>Ertrag:</strong> Hybride können hohe Erträge liefern, da sie die besten Eigenschaften ihrer Elternsorten kombinieren.</li>
                </ul>
            </section>
            
            <section id="medizinische-anwendungen" data-nav-title="Medizinische Anwendungen">
                <h4>Medizinische Anwendungen</h4>
                <p>Hybride werden häufig für medizinische Anwendungen genutzt, da sie gezielt für bestimmte therapeutische Effekte gezüchtet werden können:</p>
                <ul>
                    <li><strong>Schmerzlinderung:</strong> Hybride können sowohl körperliche Entspannung als auch geistige Anregung bieten, was sie effektiv für die Schmerzlinderung macht.</li>
                    <li><strong>Angst und Depression:</strong> Ausgewogene Hybride können helfen, die Stimmung zu verbessern und Angstzustände zu lindern, ohne übermäßige Sedierung oder Anregung zu verursachen.</li>
                    <li><strong>Schlafstörungen:</strong> Indica-dominante Hybride können helfen, Schlaflosigkeit zu behandeln, indem sie Entspannung und Schläfrigkeit fördern.</li>
                </ul>
            </section>
        ');
        $entry->setUser($user);
        $entry->setCreatedAt(new DateTimeImmutable());
        $manager->persist($entry);

        $entry = new KnowledgeBase();
        $entry->setSite('knowledge_base');
        $entry->setCategorie('growing_tips');
        $entry->setArticleName('Was bedeutet close-only strain?');
        $entry->setArticleContent('
            <p>Ein "clone-only strain" (auf Deutsch: "Nur-Klon-Sorte") ist eine Cannabissorte, die ausschließlich durch Klonen und nicht durch Samenvermehrung erhalten wird. Dies bedeutet, dass neue Pflanzen dieser Sorte nur durch das Schneiden und Wurzeln von Stecklingen einer Mutterpflanze erzeugt werden können. Hier sind einige wichtige Informationen zu "clone-only strains":</p>
    
            <section id="definition" data-nav-title="Definition">
                <h4>Definition</h4>
                <p>Ein "clone-only strain" ist eine Sorte, die aufgrund ihrer einzigartigen Eigenschaften nicht durch Samen, sondern nur durch vegetative Vermehrung weitergegeben wird. Diese Sorten sind oft das Ergebnis jahrelanger Züchtungsarbeit und besitzen spezifische Merkmale, die schwer durch Samen zu reproduzieren sind.</p>
            </section>
            
            <section id="vorteile" data-nav-title="Vorteile">
                <h4>Vorteile</h4>
                <p>Der Anbau von "clone-only strains" bietet mehrere Vorteile:</p>
                <ul>
                    <li><strong>Genetische Konsistenz:</strong> Da Klone genetisch identisch mit der Mutterpflanze sind, garantieren sie einheitliche Eigenschaften und gleichbleibende Qualität.</li>
                    <li><strong>Schnellerer Start:</strong> Klone sind bereits teilweise entwickelte Pflanzen, was den Wachstumsprozess beschleunigt im Vergleich zur Keimung von Samen.</li>
                    <li><strong>Bewahrung seltener Eigenschaften:</strong> "Clone-only strains" ermöglichen die Bewahrung und Verbreitung von einzigartigen und wertvollen genetischen Merkmalen.</li>
                </ul>
            </section>
            
            <section id="nachteile" data-nav-title="Nachteile">
                <h4>Nachteile</h4>
                <p>Es gibt auch einige Nachteile bei der Verwendung von Klonen im Vergleich zu Samen:</p>
                <ul>
                    <li><strong>Krankheitsanfälligkeit:</strong> Klone können Krankheitserreger und Schädlinge von der Mutterpflanze übernehmen.</li>
                    <li><strong>Begrenzte Verfügbarkeit:</strong> "Clone-only strains" sind oft schwerer zu bekommen als Samen, da sie nur von etablierten Züchtern oder Growern erhältlich sind.</li>
                    <li><strong>Weniger genetische Vielfalt:</strong> Klone bieten keine genetische Variation, was die Anpassungsfähigkeit an neue oder sich verändernde Anbaubedingungen einschränken kann.</li>
                </ul>
            </section>
            
            <section id="bekannte-clone-only-strains" data-nav-title="Bekannte Clone-Only Strains">
                <h4>Bekannte Clone-Only Strains</h4>
                <p>Einige berühmte "clone-only strains" haben sich aufgrund ihrer außergewöhnlichen Eigenschaften einen Namen gemacht:</p>
                <ul>
                    <li><strong>Chemdawg:</strong> Bekannt für ihr starkes Aroma und ihre potente Wirkung, ist Chemdawg eine legendäre "clone-only strain" mit unklarer Herkunft.</li>
                    <li><strong>Girl Scout Cookies (GSC):</strong> Ursprünglich als Klon verbreitet, ist GSC für ihre kraftvolle, euphorische Wirkung und ihr süßes, erdiges Aroma bekannt.</li>
                    <li><strong>OG Kush:</strong> Eine der bekanntesten Sorten, die hauptsächlich durch Klonen verbreitet wird, bekannt für ihre komplexe Genetik und starke, beruhigende Wirkung.</li>
                </ul>
            </section>
            
            <section id="klonverfahren" data-nav-title="Klonverfahren">
                <h4>Klonverfahren</h4>
                <p>Das Klonen von Cannabispflanzen umfasst mehrere Schritte, um sicherzustellen, dass die Stecklinge erfolgreich wurzeln und wachsen:</p>
                <ul>
                    <li><strong>Stecklinge schneiden:</strong> Schneide gesunde, etwa 10-15 cm lange Triebe von einer Mutterpflanze ab.</li>
                    <li><strong>Vorbereitung:</strong> Entferne die unteren Blätter und tauche das abgeschnittene Ende in ein Bewurzelungshormon.</li>
                    <li><strong>Einpflanzen:</strong> Setze die Stecklinge in ein geeignetes Medium wie Steinwolle oder Erde und halte sie feucht und warm.</li>
                    <li><strong>Pflege:</strong> Stelle sicher, dass die Stecklinge ausreichend Licht und eine hohe Luftfeuchtigkeit erhalten, bis sie Wurzeln bilden und zu eigenständigen Pflanzen werden.</li>
                </ul>
            </section>
        ');
        $entry->setUser($user);
        $entry->setCreatedAt(new DateTimeImmutable());
        $manager->persist($entry);

        $entry = new KnowledgeBase();
        $entry->setSite('knowledge_base');
        $entry->setCategorie('growing_tips');
        $entry->setArticleName('Post-Harvest Prozesse: Ein Leitfaden');
        $entry->setArticleContent('
            <p>Nach der Ernte von Cannabis ist es entscheidend, die Blüten richtig zu verarbeiten, um ihre Qualität, Potenz und Haltbarkeit zu maximieren. Hier ist ein Leitfaden zu den wichtigsten Post-Harvest Prozessen:</p>

            <section id="ernte" data-nav-title="Ernte">
                <h4>Ernte</h4>
                <p>Die Erntezeitpunkt ist entscheidend für die Qualität der Blüten. Sie erfolgt, wenn die Trichome (Harzdrüsen) den gewünschten Reifegrad erreicht haben, was durch ihre Farbe (milchig-weiß oder bernsteinfarben) erkennbar ist:</p>
                <ul>
                    <li><strong>Werkzeuge:</strong> Verwende saubere und scharfe Scheren, um die Zweige vorsichtig abzuschneiden.</li>
                    <li><strong>Vorsicht:</strong> Gehe behutsam vor, um die empfindlichen Trichome nicht zu beschädigen.</li>
                </ul>
            </section>
            
            <section id="trimmung" data-nav-title="Trimmung">
                <h4>Trimmung</h4>
                <p>Nach der Ernte müssen die Blüten getrimmt werden, um überschüssige Blätter zu entfernen und das Aussehen der Buds zu verbessern:</p>
                <ul>
                    <li><strong>Nass-Trimming:</strong> Die Blüten werden sofort nach der Ernte getrimmt, während sie noch feucht sind. Dies erleichtert die Arbeit, da die Blätter noch nicht geschrumpft sind.</li>
                    <li><strong>Trocken-Trimming:</strong> Die Blüten werden erst nach einer kurzen Trocknungsphase getrimmt. Dies kann die Trichome besser schützen, erfordert jedoch mehr Geduld.</li>
                </ul>
            </section>
            
            <section id="trocknung" data-nav-title="Trocknung">
                <h4>Trocknung</h4>
                <p>Die Trocknung ist ein kritischer Schritt, um den Wassergehalt in den Blüten zu reduzieren und Schimmelbildung zu verhindern:</p>
                <ul>
                    <li><strong>Raumtemperatur:</strong> Halte die Temperatur zwischen 15-21°C und eine relative Luftfeuchtigkeit von 45-55%.</li>
                    <li><strong>Luftzirkulation:</strong> Sorge für eine gute Belüftung, ohne direkte Luftströmungen auf die Blüten zu richten.</li>
                    <li><strong>Dauer:</strong> Die Trocknung dauert normalerweise 7-14 Tage, bis die Äste beim Biegen leicht brechen.</li>
                </ul>
            </section>
            
            <section id="fermentation" data-nav-title="Fermentation">
                <h4>Fermentation</h4>
                <p>Die Fermentation, auch Aushärtung genannt, verbessert Geschmack, Aroma und Potenz der Blüten durch den Abbau von Chlorophyll und anderen Pflanzenstoffen:</p>
                <ul>
                    <li><strong>Behälter:</strong> Verwende luftdichte Glasgefäße für die Fermentation.</li>
                    <li><strong>Belüftung:</strong> Öffne die Behälter in den ersten Wochen täglich für 15-30 Minuten, um frische Luft zuzuführen und Feuchtigkeit entweichen zu lassen.</li>
                    <li><strong>Dauer:</strong> Die Fermentation dauert mindestens 2-4 Wochen, kann aber bis zu mehreren Monaten verlängert werden, um die besten Ergebnisse zu erzielen.</li>
                </ul>
            </section>
            
            <section id="lagerung" data-nav-title="Lagerung">
                <h4>Lagerung</h4>
                <p>Nach der Fermentation müssen die Blüten richtig gelagert werden, um ihre Qualität zu bewahren:</p>
                <ul>
                    <li><strong>Dunkelheit:</strong> Lagere die Blüten an einem dunklen Ort, um den Abbau von Cannabinoiden durch Licht zu verhindern.</li>
                    <li><strong>Kühle Temperaturen:</strong> Halte die Temperatur bei 15-20°C, um die Haltbarkeit zu maximieren.</li>
                    <li><strong>Feuchtigkeit:</strong> Verwende Boveda-Packs oder ähnliche Produkte, um die relative Luftfeuchtigkeit bei etwa 58-62% zu halten.</li>
                </ul>
            </section>
            
            <section id="weiterverarbeitung" data-nav-title="Weiterverarbeitung">
                <h4>Weiterverarbeitung</h4>
                <p>Je nach Verwendungszweck können die getrockneten und fermentierten Blüten weiterverarbeitet werden:</p>
                <ul>
                    <li><strong>Extraktion:</strong> Herstellung von Konzentraten wie Öl, Wachs, Shatter oder Live Resin durch verschiedene Extraktionsmethoden.</li>
                    <li><strong>Esswaren:</strong> Infusion der Blüten in Speisen und Getränke, um essbare Cannabisprodukte herzustellen.</li>
                    <li><strong>Topische Produkte:</strong> Verwendung der Blüten zur Herstellung von Cremes, Salben und anderen Hautpflegeprodukten.</li>
                </ul>
            </section>
       ');
        $entry->setUser($user);
        $entry->setCreatedAt(new DateTimeImmutable());
        $manager->persist($entry);

        $entry = new KnowledgeBase();
        $entry->setSite('knowledge_base');
        $entry->setCategorie('consuming_tips');
        $entry->setArticleName('Welche Risiken sind mit dem Cannabiskonsum verbunden?');
        $entry->setArticleContent('
            <p>Der Konsum von Cannabis kann eine Reihe von Risiken und Nebenwirkungen mit sich bringen, die je nach Häufigkeit und Art des Konsums sowie der individuellen Empfindlichkeit variieren. Hier sind die wichtigsten Risiken des Cannabiskonsums:</p>

            <section id="kurzfristige-effekte" data-nav-title="Kurzfristige Effekte">
                <h4>Kurzfristige Effekte</h4>
                <p>Der akute Konsum von Cannabis kann eine Vielzahl von kurzfristigen Effekten haben:</p>
                <ul>
                    <li><strong>Beeinträchtigung der Kognition:</strong> Kurzzeitgedächtnis, Aufmerksamkeit und Koordinationsfähigkeit können vorübergehend beeinträchtigt werden.</li>
                    <li><strong>Psychische Effekte:</strong> Kann Angst, Paranoia, Panikattacken oder akute Psychosen auslösen, insbesondere bei hohen Dosen oder bei Personen mit einer Prädisposition für psychische Erkrankungen.</li>
                    <li><strong>Körperliche Effekte:</strong> Erhöhter Herzschlag, Mundtrockenheit, rote Augen und in einigen Fällen Schwindel oder Übelkeit.</li>
                    <li><strong>Unfallrisiko:</strong> Erhöhtes Risiko für Unfälle und Verletzungen, insbesondere im Straßenverkehr oder bei der Bedienung von Maschinen.</li>
                </ul>
            </section>
            
            <section id="langfristige-effekte" data-nav-title="Langfristige Effekte">
                <h4>Langfristige Effekte</h4>
                <p>Langfristiger, regelmäßiger Cannabiskonsum kann verschiedene gesundheitliche Auswirkungen haben:</p>
                <ul>
                    <li><strong>Abhängigkeit:</strong> Etwa 9% der Konsumenten entwickeln eine Cannabisabhängigkeit. Die Wahrscheinlichkeit ist höher bei Jugendlichen und bei täglichem Konsum.</li>
                    <li><strong>Psychische Gesundheit:</strong> Langfristiger Konsum kann das Risiko für die Entwicklung von Angststörungen, Depressionen und Psychosen, insbesondere Schizophrenie, erhöhen.</li>
                    <li><strong>Kognitive Funktion:</strong> Anhaltender Konsum kann das Lernen, das Gedächtnis und die Aufmerksamkeit beeinträchtigen, insbesondere wenn der Konsum in der Jugend beginnt.</        <li><strong>Atemwegserkrankungen:</strong> Rauchen von Cannabis kann zu chronischer Bronchitis und anderen Atemwegserkrankungen führen.</li>
                </ul>
            </section>
            
            <section id="spezielle-risikogruppen" data-nav-title="Spezielle Risikogruppen">
                <h4>Spezielle Risikogruppen</h4>
                <p>Bestimmte Personengruppen sind besonders anfällig für die negativen Auswirkungen von Cannabis:</p>
                <ul>
                    <li><strong>Jugendliche:</strong> Der Konsum in jungen Jahren kann die Gehirnentwicklung beeinträchtigen und das Risiko für Abhängigkeit und psychische Erkrankungen erhöhen.</        <li><strong>Schwangere Frauen:</strong> Cannabiskonsum während der Schwangerschaft kann das Risiko für Geburtskomplikationen und Entwicklungsprobleme beim Kind erhöhen.</li>
                    <li><strong>Menschen mit psychischen Erkrankungen:</strong> Personen mit einer Vorgeschichte von Angst, Depression oder Psychose haben ein höheres Risiko, dass Cannabis diese Zustände verschlimmert.</li>
                </ul>
            </section>
            
            <section id="interaktionen-mit-medikamenten" data-nav-title="Interaktionen mit Medikamenten">
                <h4>Interaktionen mit Medikamenten</h4>
                <p>Cannabis kann mit verschiedenen Medikamenten interagieren und deren Wirkung verändern:</p>
                <ul>
                    <li><strong>Blutdruckmedikamente:</strong> Cannabis kann den Blutdruck beeinflussen und die Wirkung von blutdrucksenkenden Medikamenten verändern.</li>
                    <li><strong>Blutverdünner:</strong> Cannabis kann die Wirkung von Blutverdünnern verstärken und das Risiko für Blutungen erhöhen.</li>
                    <li><strong>Antidepressiva und Antipsychotika:</strong> Die Kombination von Cannabis mit diesen Medikamenten kann unerwünschte Wirkungen haben und die psychische Gesundheit beeinträchtigen.</li>
                </ul>
            </section>
            
            <section id="fazit" data-nav-title="Fazit">
                <h4>Fazit</h4>
                <p>Während Cannabis für einige Menschen therapeutische Vorteile haben kann, ist es wichtig, die potenziellen Risiken und Nebenwirkungen zu berücksichtigen. Ein verantwortungsvoller Umgang und eine informierte Entscheidung sind entscheidend, um die negativen Auswirkungen des Konsums zu minimieren.</p>
            </section>
        ');
        $entry->setUser($user);
        $entry->setCreatedAt(new DateTimeImmutable());
        $manager->persist($entry);

        $entry = new KnowledgeBase();
        $entry->setSite('knowledge_base');
        $entry->setCategorie('consuming_tips');
        $entry->setArticleName('Was sollte ich tun, wenn ich zu viel Cannabis konsumiert habe?');
        $entry->setArticleContent('
            <p>Der Konsum von zu viel Cannabis kann unangenehme und beängstigende Symptome verursachen. Wenn du oder jemand in deiner Nähe zu viel Cannabis konsumiert hat, gibt es einige Maßnahmen, die du ergreifen kannst, um die Situation zu bewältigen:</p>
            
            <section id="ruhig-bleiben" data-nav-title="Ruhig bleiben">
                <h4>Ruhig bleiben</h4>
                <p>Es ist wichtig, ruhig zu bleiben und nicht in Panik zu geraten. Die Symptome eines Cannabis-Überkonsums sind in der Regel nicht lebensbedrohlich und klingen mit der Zeit ab:</p>
                <ul>
                    <li><strong>Tief durchatmen:</strong> Konzentriere dich auf langsames, tiefes Atmen, um Angst und Panik zu reduzieren.</li>
                    <li><strong>Positive Gedanken:</strong> Erinnere dich daran, dass die unangenehmen Gefühle vorübergehen werden und keine dauerhaften Schäden verursachen.</li>
                </ul>
            </section>
            
            <section id="sichere-umgebung" data-nav-title="Sichere Umgebung">
                <h4>Sichere Umgebung</h4>
                <p>Stelle sicher, dass du dich an einem sicheren und komfortablen Ort befindest:</p>
                <ul>
                    <li><strong>Beruhigende Umgebung:</strong> Gehe an einen ruhigen, vertrauten Ort, fern von Lärm und Ablenkungen.</li>
                    <li><strong>Lieber sitzen oder liegen:</strong> Setze oder lege dich hin, um Schwindel oder das Risiko von Stürzen zu vermeiden.</li>
                </ul>
            </section>
            
            <section id="flüssigkeitszufuhr" data-nav-title="Flüssigkeitszufuhr">
                <h4>Flüssigkeitszufuhr</h4>
                <p>Trinke viel Wasser, um deinen Körper hydratisiert zu halten und Mundtrockenheit zu lindern:</p>
                <ul>
                    <li><strong>Wasser oder Saft:</strong> Vermeide koffeinhaltige Getränke, die Angstgefühle verstärken können.</li>
                    <li><strong>Snacks:</strong> Ein leichter Snack kann helfen, den Blutzuckerspiegel zu stabilisieren und das Wohlbefinden zu verbessern.</li>
                </ul>
            </section>
            
            <section id="ruhig-bleiben" data-nav-title="Ablenkung">
                <h4>Ablenkung</h4>
                <p>Lenke dich mit Aktivitäten ab, die dir Freude bereiten und dich entspannen:</p>
                <ul>
                    <li><strong>Musik hören:</strong> Wähle beruhigende Musik, die du gerne hörst.</li>
                    <li><strong>Fernsehen oder Filme:</strong> Schaue dir eine leichte, unterhaltsame Sendung oder einen Film an.</li>
                    <li><strong>Bücher oder Zeitschriften:</strong> Lies etwas, das dich interessiert und entspannt.</li>
                </ul>
            </section>
            
            <section id="gesellschaft" data-nav-title="Gesellschaft">
                <h4>Gesellschaft</h4>
                <p>Es kann hilfreich sein, jemanden bei sich zu haben, dem du vertraust und der dich beruhigen kann:</p>
                <ul>
                    <li><strong>Vertrauensperson:</strong> Ein Freund oder Familienmitglied kann dir helfen, ruhig zu bleiben und sich um dich kümmern.</li>
                    <li><strong>Unterstützung:</strong> Sprich über deine Gefühle und lass dir versichern, dass alles in Ordnung ist und die Symptome vorübergehen.</li>
                </ul>
            </section>
            
            <section id="medizinische-hilfe" data-nav-title="Medizinische Hilfe">
                <h4>Medizinische Hilfe</h4>
                <p>In den meisten Fällen ist medizinische Hilfe nicht erforderlich, aber es gibt Situationen, in denen sie notwendig sein kann:</p>
                <ul>
                    <li><strong>Starke Symptome:</strong> Wenn du starke Angstzustände, Verwirrung, Brustschmerzen oder Atemnot verspürst, suche sofort medizinische Hilfe auf.</li>
                    <li><strong>Unsicherheit:</strong> Wenn du unsicher bist, ob die Symptome ernst sind, zögere nicht, einen Arzt oder Notdienst zu kontaktieren.</li>
                </ul>
            </section>
            
            <section id="prävention" data-nav-title="Prävention">
                <h4>Prävention</h4>
                <p>Um zukünftigen Überkonsum zu vermeiden, beachte folgende Tipps:</p>
                <ul>
                    <li><strong>Langsam anfangen:</strong> Beginne mit einer niedrigen Dosis und warte mindestens 1-2 Stunden, bevor du mehr konsumierst, insbesondere bei Esswaren.</li>
                    <li><strong>Informiert bleiben:</strong> Kenne die Potenz und Dosierung der Produkte, die du konsumierst.</li>
                    <li><strong>Umgebung:</strong> Konsumiere Cannabis in einer sicheren und vertrauten Umgebung, besonders wenn du unerfahren bist.</li>
                </ul>
            </section>
        ');
        $entry->setUser($user);
        $entry->setCreatedAt(new DateTimeImmutable());
        $manager->persist($entry);

        $entry = new KnowledgeBase();
        $entry->setSite('knowledge_base');
        $entry->setCategorie('consuming_tips');
        $entry->setArticleName('Kann ich Cannabis mit anderen Substanzen kombinieren?');
        $entry->setArticleContent('
            <p>Die Kombination von Cannabis mit anderen Substanzen kann sowohl beabsichtigte als auch unerwünschte Wirkungen verstärken oder abschwächen. Es ist wichtig, sich der möglichen Risiken und Wechselwirkungen bewusst zu sein, bevor man Cannabis mit anderen Substanzen kombiniert:</p>
    
            <section id="alkohol" data-nav-title="Alkohol">
                <h4>Alkohol</h4>
                <p>Die Kombination von Cannabis und Alkohol kann die Wirkungen beider Substanzen verstärken:</p>
                <ul>
                    <li><strong>Verstärkte Wirkung:</strong> Der gleichzeitige Konsum kann zu einer stärkeren Beeinträchtigung der Kognition, Koordination und Urteilsfähigkeit führen.</li>
                    <li><strong>Erhöhtes Risiko:</strong> Ein erhöhtes Risiko für Übelkeit, Erbrechen, Schwindel und Blackouts.</li>
                    <li><strong>Unvorhersehbare Effekte:</strong> Die Kombination kann unvorhersehbare Effekte haben und das Risiko für unangenehme Erlebnisse erhöhen.</li>
                </ul>
            </section>
            
            <section id="nikotin" data-nav-title="Nikotin">
                <h4>Nikotin</h4>
                <p>Viele Menschen kombinieren Cannabis mit Tabak, insbesondere in Form von Joints oder Blunts:</p>
                <ul>
                    <li><strong>Verstärkte psychoaktive Wirkung:</strong> Nikotin kann die psychoaktiven Effekte von THC verstärken.</li>
                    <li><strong>Suchtpotenzial:</strong> Die Kombination kann das Suchtpotenzial erhöhen, da sowohl Nikotin als auch Cannabis abhängig machen können.</li>
                    <li><strong>Atemwegsschäden:</strong> Rauchen von Tabak erhöht das Risiko für Atemwegserkrankungen und andere gesundheitliche Probleme.</li>
                </ul>
            </section>
            
            <section id="koffein" data-nav-title="Koffein">
                <h4>Koffein</h4>
                <p>Die Kombination von Cannabis mit koffeinhaltigen Getränken wie Kaffee oder Energydrinks ist weniger riskant, kann aber dennoch Wechselwirkungen verursachen:</p>
                <ul>
                    <li><strong>Verstärkte Wachsamkeit:</strong> Koffein kann die sedierenden Effekte von Cannabis ausgleichen und die Wachsamkeit erhöhen.</li>
                    <li><strong>Angst und Nervosität:</strong> Bei manchen Menschen kann die Kombination zu erhöhter Angst und Nervosität führen.</li>
                    <li><strong>Individuelle Reaktionen:</strong> Die Effekte können je nach individueller Empfindlichkeit variieren.</li>
                </ul>
            </section>
            
            <section id="medikamente" data-nav-title="Medikamente">
                <h4>Medikamente</h4>
                <p>Cannabis kann mit verschiedenen Medikamenten interagieren, was die Wirkung dieser Medikamente verändern kann:</p>
                <ul>
                    <li><strong>Blutdruckmedikamente:</strong> Cannabis kann den Blutdruck beeinflussen und die Wirkung von blutdrucksenkenden Medikamenten verändern.</li>
                    <li><strong>Blutverdünner:</strong> Cannabis kann die Wirkung von Blutverdünnern verstärken und das Risiko für Blutungen erhöhen.</li>
                    <li><strong>Antidepressiva und Antipsychotika:</strong> Die Kombination von Cannabis mit diesen Medikamenten kann unerwünschte Wirkungen haben und die psychische Gesundheit beeinträchtigen.</li>
                    <li><strong>Schmerzmittel:</strong> Cannabis kann die Wirkung von Schmerzmitteln verstärken oder verändern, was zu unvorhersehbaren Effekten führen kann.</li>
                </ul>
            </section>
            
            <section id="psychedelika" data-nav-title="Psychedelika">
                <h4>Psychedelika</h4>
                <p>Die Kombination von Cannabis mit Psychedelika wie LSD, Psilocybin (Magic Mushrooms) oder MDMA kann die Effekte verstärken und zu intensiveren Erlebnissen führen:</p>
                <ul>
                    <li><strong>Verstärkte Halluzinationen:</strong> Cannabis kann die halluzinogenen Effekte von Psychedelika verstärken.</li>
                    <li><strong>Erhöhte Angst:</strong> Die Kombination kann bei manchen Menschen zu erhöhter Angst und Paranoia führen.</li>
                    <li><strong>Unvorhersehbare Effekte:</strong> Die Effekte sind oft unvorhersehbar und können von Person zu Person stark variieren.</li>
                </ul>
            </section>
            
            <section id="fazit" data-nav-title="Fazit">
                <h4>Fazit</h4>
                <p>Die Kombination von Cannabis mit anderen Substanzen sollte mit Vorsicht angegangen werden, da die Wechselwirkungen unvorhersehbare und potenziell gefährliche Effekte haben können. Es ist wichtig, die individuellen Reaktionen des Körpers zu verstehen und im Zweifelsfall einen Arzt oder Apotheker zu konsultieren.</p>
            </section>
        ');
        $entry->setUser($user);
        $entry->setCreatedAt(new DateTimeImmutable());
        $manager->persist($entry);

        $entry = new KnowledgeBase();
        $entry->setSite('knowledge_base');
        $entry->setCategorie('consuming_tips');
        $entry->setArticleName('Welche gesetzlichen Regelungen gibt es?');
        $entry->setArticleContent('
            <p>Die gesetzlichen Regelungen für Cannabis variieren weltweit erheblich und hängen stark von den jeweiligen Ländern und Regionen ab. Hier sind einige allgemeine Informationen zu den gesetzlichen Regelungen bezüglich des Anbaus, Besitzes, Verkaufs und Konsums von Cannabis:</p>
            
            <section id="international" data-nav-title="International">
                <h4>International</h4>
                <p>Auf internationaler Ebene wird Cannabis durch verschiedene Übereinkommen und Verträge geregelt:</p>
                <ul>
                    <li><strong>Einheitsabkommen über die Betäubungsmittel von 1961:</strong> Dieses internationale Abkommen klassifiziert Cannabis als kontrollierte Substanz und verlangt von den Unterzeichnerstaaten, dessen Produktion und Vertrieb zu regulieren.</li>
                    <li><strong>Übereinkommen über psychotrope Substanzen von 1971:</strong> Cannabis und seine Derivate sind in diesem Abkommen ebenfalls als kontrollierte Substanzen aufgeführt.</li>
                    <li><strong>UN-Übereinkommen gegen den unerlaubten Verkehr mit Suchtstoffen und psychotropen Stoffen von 1988:</strong> Dieses Abkommen zielt darauf ab, den illegalen Handel mit Drogen, einschließlich Cannabis, zu bekämpfen.</li>
                </ul>
            </section>
            
            <section id="europa" data-nav-title="Europa">
                <h4>Europa</h4>
                <p>In Europa variieren die gesetzlichen Regelungen für Cannabis von Land zu Land:</p>
                <ul>
                    <li><strong>Niederlande:</strong> Der Besitz kleiner Mengen und der Verkauf in Coffeeshops sind entkriminalisiert, jedoch bleibt der Anbau und Großhandel illegal.</li>
                    <li><strong>Deutschland:</strong> Medizinisches Cannabis ist legal und kann auf Rezept verschrieben werden. Der Besitz kleiner Mengen für den persönlichen Gebrauch ist in einigen Bundesländern entkriminalisiert.</li>
                    <li><strong>Spanien:</strong> Der private Anbau und Konsum in Cannabis Social Clubs ist erlaubt, der öffentliche Verkauf jedoch nicht.</li>
                    <li><strong>Portugal:</strong> Der Besitz kleiner Mengen für den persönlichen Gebrauch ist entkriminalisiert, jedoch bleibt der Verkauf illegal.</li>
                </ul>
            </section>
            
            <section id="nordamerika" data-nav-title="Nordamerika">
                <h4>Nordamerika</h4>
                <p>In Nordamerika gibt es ebenfalls unterschiedliche Regelungen in den USA und Kanada:</p>
                <ul>
                    <li><strong>USA:</strong> Die gesetzlichen Regelungen variieren von Staat zu Staat. Einige Staaten haben Cannabis für den medizinischen und/oder freizeitlichen Gebrauch legalisiert, während es auf Bundesebene weiterhin illegal ist.</li>
                    <li><strong>Kanada:</strong> Cannabis ist seit 2018 landesweit für den medizinischen und freizeitlichen Gebrauch legal. Der Besitz, Verkauf und Anbau sind unter bestimmten Auflagen erlaubt.</li>
                </ul>
            </section>
            
            <section id="südamerika" data-nav-title="Südamerika">
                <h4>Südamerika</h4>
                <p>Auch in Südamerika gibt es unterschiedliche Regelungen:</p>
                <ul>
                    <li><strong>Uruguay:</strong> Uruguay war das erste Land, das Cannabis 2013 vollständig legalisiert hat. Der Anbau, Verkauf und Konsum sind reguliert und legal.</li>
                    <li><strong>Argentinien:</strong> Medizinisches Cannabis ist legal, und der Anbau für den persönlichen Gebrauch wurde teilweise entkriminalisiert.</li>
                    <li><strong>Brasilien:</strong> Der medizinische Gebrauch ist unter strengen Auflagen erlaubt, jedoch bleibt der Freizeitkonsum illegal.</li>
                </ul>
            </section>
            
            <section id="asien" data-nav-title="Asien">
                <h4>Asien</h4>
                <p>In Asien sind die Regelungen oft strenger:</p>
                <ul>
                    <li><strong>Thailand:</strong> Medizinisches Cannabis ist seit 2018 legal, und es gibt Pläne zur weiteren Liberalisierung.</li>
                    <li><strong>Indien:</strong> Traditionell wird Cannabis in einigen Regionen für religiöse Zwecke verwendet, der Konsum und Anbau bleiben jedoch weitgehend illegal.</li>
                    <li><strong>China und Japan:</strong> Cannabis ist strikt verboten, und der Besitz kann zu schweren Strafen führen.</li>
                </ul>
            </section>
            
            <section id="afrika" data-nav-title="Afrika">
                <h4>Afrika</h4>
                <p>In Afrika variieren die Regelungen ebenfalls stark:</p>
                <ul>
                    <li><strong>Südafrika:</strong> Der private Anbau und Konsum für den persönlichen Gebrauch wurden 2018 vom Verfassungsgericht entkriminalisiert.</li>
                    <li><strong>Lesotho:</strong> Lesotho war das erste afrikanische Land, das den Anbau von medizinischem Cannabis legalisierte.</li>
                    <li><strong>Marokko:</strong> Marokko ist ein bedeutender Produzent von illegalem Cannabis, aber es gibt Bestrebungen, den Anbau für medizinische Zwecke zu legalisieren.</li>
                </ul>
            </section>
            
            <section id="fazit" data-nav-title="Fazit">
                <h4>Fazit</h4>
                <p>Die gesetzlichen Regelungen für Cannabis sind weltweit sehr unterschiedlich und ändern sich ständig. Es ist wichtig, sich über die spezifischen Gesetze in der eigenen Region oder dem Reiseland zu informieren, um rechtliche Konsequenzen zu vermeiden.</p>
            </section>
        ');
        $entry->setUser($user);
        $entry->setCreatedAt(new DateTimeImmutable());
        $manager->persist($entry);

        $entry = new KnowledgeBase();
        $entry->setSite('knowledge_base');
        $entry->setCategorie('consuming_tips');
        $entry->setArticleName('Welche Gefahren gibt es?');
        $entry->setArticleContent('
            <p>Der Konsum von Cannabis kann verschiedene Gefahren und Risiken mit sich bringen, die sowohl physische als auch psychische Auswirkungen haben können. Hier sind die wichtigsten Gefahren des Cannabiskonsums:</p>
    
            <section id="physische-gefahren" data-nav-title="Physische Gefahren">
                <h4>Physische Gefahren</h4>
                <p>Der Konsum von Cannabis kann eine Reihe von physischen Gesundheitsrisiken mit sich bringen:</p>
                <ul>
                    <li><strong>Atemwegserkrankungen:</strong> Das Rauchen von Cannabis kann die Atemwege reizen und das Risiko für chronische Bronchitis und andere Atemwegserkrankungen erhöhen.</li>
                    <li><strong>Herz-Kreislauf-Probleme:</strong> Cannabis kann den Herzschlag beschleunigen und den Blutdruck erhöhen, was besonders bei Personen mit bestehenden Herz-Kreislauf-Erkrankungen problematisch sein kann.</li>
                    <li><strong>Geschwächtes Immunsystem:</strong> Langfristiger Cannabiskonsum kann das Immunsystem beeinträchtigen und die Anfälligkeit für Infektionen erhöhen.</li>
                </ul>
            </section>
            
            <section id="psychische-gefahren" data-nav-title="Psychische Gefahren">
                <h4>Psychische Gefahren</h4>
                <p>Der Konsum von Cannabis kann erhebliche psychische Auswirkungen haben, insbesondere bei regelmäßigem Gebrauch:</p>
                <ul>
                    <li><strong>Abhängigkeit und Sucht:</strong> Etwa 9% der Cannabiskonsumenten entwickeln eine Abhängigkeit. Die Wahrscheinlichkeit ist höher bei Jugendlichen und bei täglichem Konsum.</li>
                    <li><strong>Angst und Paranoia:</strong> Hohe Dosen von THC können Angstzustände, Paranoia und in einigen Fällen Panikattacken auslösen.</li>
                    <li><strong>Psychosen:</strong> Langfristiger Cannabiskonsum, insbesondere in jungen Jahren, kann das Risiko für Psychosen und Schizophrenie erhöhen, insbesondere bei Personen mit einer genetischen Veranlagung.</li>
                </ul>
            </section>
            
            <section id="kognitive-gefahren" data-nav-title="Kognitive Gefahren">
                <h4>Kognitive Gefahren</h4>
                <p>Der Cannabiskonsum kann sich negativ auf die kognitive Funktion auswirken:</p>
                <ul>
                    <li><strong>Beeinträchtigung des Gedächtnisses:</strong> Kurzzeitgedächtnis und Lernfähigkeit können beeinträchtigt werden, insbesondere bei regelmäßigem Konsum.</li>
                    <li><strong>Verminderte Aufmerksamkeit:</strong> Langfristiger Konsum kann die Aufmerksamkeit und Konzentration beeinträchtigen.</li>
                    <li><strong>Verminderte Entscheidungsfähigkeit:</strong> Cannabis kann die Fähigkeit beeinträchtigen, rationale Entscheidungen zu treffen, was zu riskantem Verhalten führen kann.</li>
                </ul>
            </section>
            
            <section id="sozial-gefahren" data-nav-title="Soziale Gefahren">
                <h4>Soziale Gefahren</h4>
                <p>Der Konsum von Cannabis kann auch soziale Auswirkungen haben:</p>
                <ul>
                    <li><strong>Berufliche Konsequenzen:</strong> Regelmäßiger Konsum kann die berufliche Leistung beeinträchtigen und zu Disziplinarmaßnahmen oder Arbeitsplatzverlust führen.</li>
                    <li><strong>Beziehungsprobleme:</strong> Häufiger Konsum kann Spannungen und Konflikte in persönlichen Beziehungen verursachen.</li>
                    <li><strong>Rechtliche Probleme:</strong> In vielen Ländern ist der Besitz, Anbau und Verkauf von Cannabis illegal, was zu strafrechtlichen Konsequenzen führen kann.</li>
                </ul>
            </section>
            
            <section id="sicherheitsrisiken" data-nav-title="Sicherheitsrisiken">
                <h4>Sicherheitsrisiken</h4>
                <p>Der Konsum von Cannabis kann die Sicherheit gefährden, insbesondere in bestimmten Situationen:</p>
                <ul>
                    <li><strong>Fahren unter Einfluss:</strong> Cannabis beeinträchtigt die Reaktionszeit und das Urteilsvermögen, was das Unfallrisiko im Straßenverkehr erheblich erhöht.</li>
                    <li><strong>Bedienung von Maschinen:</strong> Der Konsum kann die Fähigkeit zur sicheren Bedienung von Maschinen und zur Ausführung gefährlicher Arbeiten beeinträchtigen.</li>
                </ul>
            </section>
            
            <section id="wechselwirkungen-mit-medikamenten" data-nav-title="Wechselwirkungen mit Medikamenten">
                <h4>Wechselwirkungen mit Medikamenten</h4>
                <p>Cannabis kann mit verschiedenen Medikamenten interagieren und deren Wirkung verändern:</p>
                <ul>
                    <li><strong>Blutdruckmedikamente:</strong> Cannabis kann den Blutdruck beeinflussen und die Wirkung von blutdrucksenkenden Medikamenten verändern.</li>
                    <li><strong>Blutverdünner:</strong> Cannabis kann die Wirkung von Blutverdünnern verstärken und das Risiko für Blutungen erhöhen.</li>
                    <li><strong>Antidepressiva und Antipsychotika:</strong> Die Kombination von Cannabis mit diesen Medikamenten kann unerwünschte Wirkungen haben und die psychische Gesundheit beeinträchtigen.</li>
                </ul>
            </section>
            
            <section id="fazit" data-nav-title="Fazit">
                <h4>Fazit</h4>
                <p>Obwohl Cannabis für einige Menschen therapeutische Vorteile haben kann, ist es wichtig, sich der potenziellen Gefahren und Risiken bewusst zu sein. Ein verantwortungsvoller Umgang und eine informierte Entscheidung sind entscheidend, um die negativen Auswirkungen des Konsums zu minimieren.</p>
            </section>
        ');
        $entry->setUser($user);
        $entry->setCreatedAt(new DateTimeImmutable());
        $manager->persist($entry);

        $entry = new KnowledgeBase();
        $entry->setSite('knowledge_base');
        $entry->setCategorie('Cannabis-Consumption-Compliance-Map');
        $entry->setArticleName('Cannabis Consumption Compliance Map');
        $entry->setArticleContent('This is how you use Cannabis Consumption Compliance Map');
        $entry->setUser($user);
        $entry->setCreatedAt(new DateTimeImmutable());
        $manager->persist($entry);

        $entry = new KnowledgeBase();
        $entry->setSite('knowledge_base');
        $entry->setCategorie('Social-Club');
        $entry->setArticleName('Social Club');
        $entry->setArticleContent('This is how you use Social Club');
        $entry->setUser($user);
        $entry->setCreatedAt(new DateTimeImmutable());
        $manager->persist($entry);

        $entry = new KnowledgeBase();
        $entry->setSite('knowledge_base');
        $entry->setCategorie('Bud-Bash-Locator');
        $entry->setArticleName('Bud Bash Locator');
        $entry->setArticleContent('This is how you use Bud Bash Locator');
        $entry->setUser($user);
        $entry->setCreatedAt(new DateTimeImmutable());
        $manager->persist($entry);

        $entry = new KnowledgeBase();
        $entry->setSite('knowledge_base');
        $entry->setCategorie('Cannastrain-Library');
        $entry->setArticleName('Cannastrain Library');
        $entry->setArticleContent('This is how you use Cannastrain Library');
        $entry->setUser($user);
        $entry->setCreatedAt(new DateTimeImmutable());
        $manager->persist($entry);

        $entry = new KnowledgeBase();
        $entry->setSite('knowledge_base');
        $entry->setCategorie('Cannadose-Calculator');
        $entry->setArticleName('Cannadose Calculator');
        $entry->setArticleContent('This is how you use Cannadose Calculator');
        $entry->setUser($user);
        $entry->setCreatedAt(new DateTimeImmutable());
        $manager->persist($entry);

        $entry = new KnowledgeBase();
        $entry->setSite('knowledge_base');
        $entry->setCategorie('Grow-Mate');
        $entry->setArticleName('Grow Mate');
        $entry->setArticleContent('This is how you use Grow Mate');
        $entry->setUser($user);
        $entry->setCreatedAt(new DateTimeImmutable());
        $manager->persist($entry);

        $entry = new KnowledgeBase();
        $entry->setSite('knowledge_base');
        $entry->setCategorie('Canna-Consultant');
        $entry->setArticleName('CannaConsultant');
        $entry->setArticleContent('This is how you use CannaConsultant');
        $entry->setUser($user);
        $entry->setCreatedAt(new DateTimeImmutable());
        $manager->persist($entry);

        $manager->flush();
    }
}

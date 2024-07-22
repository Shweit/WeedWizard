<?php

namespace App\DataFixtures;

use App\Entity\CannabisVerein;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ClubFixtures extends Fixture
{
    public function __construct(
        private readonly UserPasswordHasherInterface $passwordHasher,
    ) {}

    public function load(ObjectManager $manager): void
    {
        $names = [
            'Grüner Genuss Club',
            'Cannabis Freunde e.V.',
            'Hanf Harmonie Verein',
            'CBD Relax Club',
            'Cannabis Kultur Verein',
            'Hanf und Heil Verein',
            'Green Spirit Gemeinschaft',
            'Cannabis Kreise e.V.',
            'Hanf Freude Club',
            'Cannabis Wissen e.V.',
        ];

        $emails = [
            'kontakt@gruenergenussclub.de',
            'info@cannabisfreunde.de',
            'service@hanfharmonieverein.de',
            'support@cbdrelaxclub.de',
            'kontakt@cannabiskulturverein.de',
            'info@hanfundheilverein.de',
            'kontakt@greenspiritgemeinschaft.de',
            'info@cannabiskreise.de',
            'kontakt@hanffreudeclub.de',
            'info@cannabiswissen.de',
        ];

        $descriptions = [
            'Grüner Genuss Club: Entspannung und Gemeinschaft in einer friedlichen Atmosphäre.',
            'Cannabis Freunde e.V.: Treffpunkt für Enthusiasten und Wissensaustausch.',
            'Hanf Harmonie Verein: Förderung von nachhaltigem Hanfanbau und -konsum.',
            'CBD Relax Club: Stressabbau und Wellness durch natürliche Produkte.',
            'Cannabis Kultur Verein: Veranstaltungen und Workshops rund um die Hanfpflanze.',
            'Hanf und Heil Verein: Schwerpunkt auf die medizinische Nutzung von Cannabis.',
            'Green Spirit Gemeinschaft: Unterstützung für legale und sichere Konsummöglichkeiten.',
            'Cannabis Kreise e.V.: Diskussionen und Informationsabende zu aktuellen Themen.',
            'Hanf Freude Club: Freizeitaktivitäten und gesellige Treffen für Cannabisliebhaber.',
            'Cannabis Wissen e.V.: Aufklärung und Bildung rund um die Nutzung und Vorteile von Cannabis.',
        ];

        $addresses = [
            'Kurfürstendamm 10, 10719 Berlin $ 52.5034,13.3323',
            'Marienplatz 1, 80331 München $ 48.1374,11.5755',
            'Schildergasse 20, 50667 Köln $ 50.9363,6.9541',
            'Konigstraße 70, 70173 Stuttgart $ 48.7786,9.1801',
            'Zeil 50, 60313 Frankfurt am Main $ 50.1144,8.6782',
            'Mönckebergstraße 7, 20095 Hamburg $ 53.5511,10.0011',
            'Königsallee 15, 40212 Düsseldorf $ 51.2233,6.7828',
            'Altstadtmarkt 10, 38100 Braunschweig $ 52.2659,10.5238',
            'Luisenstraße 5, 30159 Hannover $ 52.3759,9.7320',
            'Bahnhofstraße 16, 40210 Düsseldorf $ 51.2194,6.7933',
        ];

        $urls = [
            'www.gruenergenussclub.de',
            'www.cannabisfreunde.de',
            'www.hanfharmonieverein.de',
            'www.cbdrelaxclub.de',
            'www.cannabiskulturverein.de',
            'www.hanfundheilverein.de',
            'www.greenspiritgemeinschaft.de',
            'www.cannabiskreise.de',
            'www.hanffreudeclub.de',
            'www.cannabiswissen.de',
        ];

        $sonstiges = [
            'Mitgliederzahl: 150',
            null,
            'Gegründet: 2010',
            null,
            'Veranstaltungen: Wöchentlich',
            null,
            'Anbauworkshops',
            null,
            null,
            'Öffnungszeiten: Mo-Fr 10-18 Uhr',
        ];

        for ($i = 0; $i < 10; ++$i) {
            $user = new User();

            $user->setFirstname('User-' . $i);
            $user->setLastname('User-' . $i);
            $user->setBirthdate(new \DateTime('now - ' . rand(18, 50) . ' years'));
            $user->setEmail($emails[$i]);
            $user->setPassword(
                $this->passwordHasher->hashPassword(
                    $user,
                    'SicheresPasswort'
                )
            );
            $user->setUsername('user-' . ($i + 1));

            $address = explode(' $ ', $addresses[$i]);

            $club = new CannabisVerein();
            $club->setName($names[$i]);
            $club->setAdresse($address[0]);
            $club->setCoordinaten($address[1]);
            $club->setWebsite($urls[$i]);
            $club->setMitgliedsbeitrag(strval(rand(100, 10000) / 100));
            $club->setBeschreibung($descriptions[$i]);
            $club->setSonstiges($sonstiges[$i]);
            $club->setCreatedBy($user);
            $club->addParticipant($user);

            $manager->persist($user);
            $manager->persist($club);
        }

        $manager->flush();
    }
}

<?php

namespace App\DataFixtures;

use App\Entity\TeamMember;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{

    public function load(ObjectManager $manager)
    {
        $teamMember1 = new TeamMember();
        $teamMember1->setFirstName('Aghilas');
        $teamMember1->setLastName('IZEM');
        $teamMember1->setEmail('izemaghilas@gmail.com');
        $teamMember1->setBirthDate(new DateTime('2000-10-25'));
        $manager->persist($teamMember1);
        
        $teamMember2 = new TeamMember();
        $teamMember2->setFirstName('Afulay');
        $teamMember2->setLastName('AMEKSA');
        $teamMember2->setEmail('ameksaafulay@gmail.com');
        $teamMember2->setBirthDate(new DateTime('2010-01-05'));
        $manager->persist($teamMember2);

        $manager->flush();
    }
}

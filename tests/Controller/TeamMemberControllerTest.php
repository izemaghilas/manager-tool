<?php

namespace App\Test;

use Faker\Factory;
use Faker\Generator;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TeamMemberControllerTest extends WebTestCase
{
    private const TEAM_MEMBERS_URL = '/team-members';
    
    private Generator $faker;
    private KernelBrowser $client;

    protected function setup(): void
    {
        $this->faker = Factory::create();
        $this->client = static::createClient();
    }

    public function testShowTeamMembers(): void
    {
        $crawler = $this->client->request('GET', self::TEAM_MEMBERS_URL);

        $teamMembers = $crawler->filter('body > div')->children();
        $firstTeamMember = $teamMembers->eq(0)->children();
        $secondTeamMember = $teamMembers->eq(1)->children();
        
        $this->assertSame('Aghilas', $firstTeamMember->eq(0)->text());
        $this->assertSame('IZEM', $firstTeamMember->eq(1)->text());
        $this->assertSame('izemaghilas@gmail.com', $firstTeamMember->eq(2)->text());
        $this->assertSame('2000-10-25', $firstTeamMember->eq(3)->text());
        
        $this->assertSame('Afulay', $secondTeamMember->eq(0)->text());
        $this->assertSame('AMEKSA', $secondTeamMember->eq(1)->text());
        $this->assertSame('ameksaafulay@gmail.com', $secondTeamMember->eq(2)->text());
        $this->assertSame('2010-01-05', $secondTeamMember->eq(3)->text());
        
    }

    public function testLinkToTeamMemberHirePage(): void
    {
        $this->client->request('GET', self::TEAM_MEMBERS_URL);
        $crawler = $this->client->clickLink('Hire');
        
        $this->assertResponseIsSuccessful();
        $this->assertPageTitleSame('Hire Team Member');
        $this->assertCount(1, $crawler->filter('form'));
    }

    public function testHireNewTeamMemberWithValidData(): void
    {
        $firstName = $this->faker->firstName();
        $lastName = $this->faker->lastName();
        $email = $this->faker->email();
        $birthDate = $this->faker->date();

        $this->client->request('GET', self::TEAM_MEMBERS_URL.'/hire');
        $this->client->submitForm('Hire', [
            'team_member[firstName]' => $firstName,
            'team_member[lastName]' => $lastName,
            'team_member[email]' => $email,
            'team_member[birthDate]' => $birthDate
        ]);

        $this->assertResponseRedirects(self::TEAM_MEMBERS_URL);
        
        $crawler = $this->client->followRedirect();
        $teamMembers = $crawler->filter('body > div')->children();
        $newTeamMember = $teamMembers->eq(2)->children();

        $this->assertSame($firstName, $newTeamMember->eq(0)->text());
        $this->assertSame($lastName, $newTeamMember->eq(1)->text());
        $this->assertSame($email, $newTeamMember->eq(2)->text());
        $this->assertSame($birthDate, $newTeamMember->eq(3)->text());
    }

    public function testHireNewTeamMemberFailedDueToNoFirstName(): void
    {
        $this->client->request('GET', self::TEAM_MEMBERS_URL.'/hire');
        $this->client->submitForm('Hire', [
            'team_member[lastName]' => $this->faker->lastName(),
            'team_member[email]' => $this->faker->email(),
            'team_member[birthDate]' => $this->faker->date()
        ]); 
        
        $this->assertResponseIsUnprocessable();
        $this->assertSelectorTextContains('li', 'Enter team member first name.');
    }

    public function testHireNewTeamMemberFailedDueToNoLastName(): void
    {
        $this->client->request('GET', self::TEAM_MEMBERS_URL.'/hire');
        $this->client->submitForm('Hire', [
            'team_member[firstName]' => $this->faker->firstName(),
            'team_member[email]' => $this->faker->email(),
            'team_member[birthDate]' => $this->faker->date()
        ]);
        
        $this->assertResponseIsUnprocessable();
        $this->assertSelectorTextContains('li', 'Enter team member last name.');
    }

    public function testHireNewTeamMemberFailedDueToNoEmail(): void
    {
        $this->client->request('GET', self::TEAM_MEMBERS_URL.'/hire');
        $this->client->submitForm('Hire', [
            'team_member[firstName]' => $this->faker->firstName(),
            'team_member[lastName]' => $this->faker->lastName(),
            'team_member[birthDate]' => $this->faker->date()
        ]);

        $this->assertResponseIsUnprocessable();
        $this->assertSelectorTextContains('li', 'Enter team member email address.');
    }

    public function testHireNewTeamMemberFailedDueToInvalidEmail(): void
    {
        $this->client->request('GET', self::TEAM_MEMBERS_URL.'/hire');
        $this->client->submitForm('Hire', [
            'team_member[firstName]' => $this->faker->firstName(),
            'team_member[lastName]' => $this->faker->lastName(),
            'team_member[email]' => 'dsdsd@fkl',
            'team_member[birthDate]' => $this->faker->date()
        ]);
        
        $this->assertResponseIsUnprocessable();
        $this->assertSelectorTextContains('li', 'Enter valid email address.');
    }

    public function testHireNewTeamMemberFailedDueToNoBirthDate(): void
    {
        $this->client->request('GET', self::TEAM_MEMBERS_URL.'/hire');
        $this->client->submitForm('Hire', [
            'team_member[firstName]' => $this->faker->firstName(),
            'team_member[lastName]' => $this->faker->lastName(),
            'team_member[email]' => $this->faker->email()
        ]);

        $this->assertResponseIsUnprocessable();
        $this->assertSelectorTextContains('li', 'Enter team member birth date.');
    }

    public function testHireNewTeamMemberFailedDueToInvalidBirthDate(): void
    {
        $this->client->request('GET', self::TEAM_MEMBERS_URL.'/hire');
        $this->client->submitForm('Hire', [
            'team_member[firstName]' => $this->faker->firstName(),
            'team_member[lastName]' => $this->faker->lastName(),
            'team_member[email]' => $this->faker->email(),
            'team_member[birthDate]' => 'test'
        ]);

        $this->assertResponseIsUnprocessable();
        $this->assertSelectorTextContains('li', 'Invalid birth date.');
    }

    public function testDeleteTeamMemberSuccessful(): void
    {
        $this->client->request('GET', self::TEAM_MEMBERS_URL);
        $this->client->clickLink('delete');

        $this->assertResponseRedirects(self::TEAM_MEMBERS_URL);
        
        $crawler = $this->client->followRedirect();
        $teamMembers = $crawler->filter('body > div')->children();
        $firstTeamMember = $teamMembers->eq(0)->children();

        $this->assertCount(1, $teamMembers);
        $this->assertSame('Afulay', $firstTeamMember->eq(0)->text());
        $this->assertSame('AMEKSA', $firstTeamMember->eq(1)->text());
        $this->assertSame('ameksaafulay@gmail.com', $firstTeamMember->eq(2)->text());
        $this->assertSame('2010-01-05', $firstTeamMember->eq(3)->text());
    }
    
    public function testDeleteTeamMemberNotOnDataBase(): void
    {
        $this->client->request('GET', self::TEAM_MEMBERS_URL.'/12/delete');

        $this->assertResponseRedirects('/team-members');

        $crawler = $this->client->followRedirect();
        $teamMembers = $crawler->filter('body > div')->children();
        $firstTeamMember = $teamMembers->eq(0)->children();
        $secondTeamMember = $teamMembers->eq(1)->children();

        $this->assertCount(2, $teamMembers);
        $this->assertSame('Aghilas', $firstTeamMember->eq(0)->text());
        $this->assertSame('IZEM', $firstTeamMember->eq(1)->text());
        $this->assertSame('izemaghilas@gmail.com', $firstTeamMember->eq(2)->text());
        $this->assertSame('2000-10-25', $firstTeamMember->eq(3)->text());
        
        $this->assertSame('Afulay', $secondTeamMember->eq(0)->text());
        $this->assertSame('AMEKSA', $secondTeamMember->eq(1)->text());
        $this->assertSame('ameksaafulay@gmail.com', $secondTeamMember->eq(2)->text());
        $this->assertSame('2010-01-05', $secondTeamMember->eq(3)->text());
    }

    public function testEditTeamMemberSuccessful(): void
    {
        $firstName = $this->faker->firstName();
        $lastName = $this->faker->lastName();
        $email = $this->faker->email();
        $birthDate = $this->faker->date();

        $this->client->request('GET', self::TEAM_MEMBERS_URL);
        $crawler = $this->client->clickLink('edit');
        $form = $crawler->selectButton('Edit')->form();
        
        $this->client->submitForm('Edit', [
            'team_member[firstName]' => $firstName,
            'team_member[lastName]' => $lastName,
            'team_member[email]' => $email,
            'team_member[birthDate]' => $birthDate
        ]);

        $crawler = $this->client->followRedirect();
        $teamMembers = $crawler->filter('body > div')->children();
        $firstTeamMember = $teamMembers->eq(0)->children();
        $secondTeamMember = $teamMembers->eq(1)->children();

        $this->assertSame('Aghilas', $form['team_member[firstName]']->getValue());
        $this->assertSame('IZEM', $form['team_member[lastName]']->getValue());
        $this->assertSame('izemaghilas@gmail.com', $form['team_member[email]']->getValue());
        $this->assertSame('2000-10-25', $form['team_member[birthDate]']->getValue());

        $this->assertCount(2, $teamMembers);
        $this->assertSame($firstName, $firstTeamMember->eq(0)->text());
        $this->assertSame($lastName, $firstTeamMember->eq(1)->text());
        $this->assertSame($email, $firstTeamMember->eq(2)->text());
        $this->assertSame($birthDate, $firstTeamMember->eq(3)->text());
        
        $this->assertSame('Afulay', $secondTeamMember->eq(0)->text());
        $this->assertSame('AMEKSA', $secondTeamMember->eq(1)->text());
        $this->assertSame('ameksaafulay@gmail.com', $secondTeamMember->eq(2)->text());
        $this->assertSame('2010-01-05', $secondTeamMember->eq(3)->text());
    }

    public function testEditTeamMemberNotOnDataBase(): void
    {
        $this->client->request('POST', self::TEAM_MEMBERS_URL.'/12/edit');
        
        $crawler = $this->client->followRedirect();
        $teamMembers = $crawler->filter('body > div')->children();
        $firstTeamMember = $teamMembers->eq(0)->children();
        $secondTeamMember = $teamMembers->eq(1)->children();

        $this->assertCount(2, $teamMembers);
        
        $this->assertSame('Aghilas', $firstTeamMember->eq(0)->text());
        $this->assertSame('IZEM', $firstTeamMember->eq(1)->text());
        $this->assertSame('izemaghilas@gmail.com', $firstTeamMember->eq(2)->text());
        $this->assertSame('2000-10-25', $firstTeamMember->eq(3)->text());
        
        $this->assertSame('Afulay', $secondTeamMember->eq(0)->text());
        $this->assertSame('AMEKSA', $secondTeamMember->eq(1)->text());
        $this->assertSame('ameksaafulay@gmail.com', $secondTeamMember->eq(2)->text());
        $this->assertSame('2010-01-05', $secondTeamMember->eq(3)->text());
    }
}
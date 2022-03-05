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

    public function testTeamMembersPage(): void
    {
        $crawler = $this->client->request('GET', self::TEAM_MEMBERS_URL);
        
        $this->assertResponseIsSuccessful();
        $this->assertPageTitleSame('Team Members');
        $this->assertCount(1, $crawler->filter('a'));
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
        $this->client->request('GET', self::TEAM_MEMBERS_URL.'/hire');
        $this->client->submitForm('Hire', [
            'team_member[firstName]' => $this->faker->firstName(),
            'team_member[lastName]' => $this->faker->lastName(),
            'team_member[email]' => $this->faker->email(),
            'team_member[birthDate]' => $this->faker->date('Y-m-d')
        ]);

        $this->assertResponseRedirects(self::TEAM_MEMBERS_URL);
        // $this->client->followRedirect();
        // assert team member was successfully created 
    }

    public function testHireNewTeamMemberFailedDueToNoFirstName(): void
    {
        $this->client->request('GET', self::TEAM_MEMBERS_URL.'/hire');
        $this->client->submitForm('Hire', [
            'team_member[lastName]' => $this->faker->lastName(),
            'team_member[email]' => $this->faker->email(),
            'team_member[birthDate]' => $this->faker->date('Y-m-d')
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
            'team_member[birthDate]' => $this->faker->date('Y-m-d')
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
            'team_member[birthDate]' => $this->faker->date('Y-m-d')
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
            'team_member[birthDate]' => $this->faker->date('Y-m-d')
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
}
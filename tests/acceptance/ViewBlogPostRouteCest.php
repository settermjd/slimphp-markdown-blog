<?php

class ViewBlogPostRouteCest
{
    public function tryToTest(AcceptanceTester $I)
    {
        $I->amOnPage('/item/hello-world');
        $I->seeResponseCodeIs(200);
        $I->seeInTitle(
            'Hey! Welcome to the blog!'
        );
        $I->see('Hey! Welcome to the blog!', '//h1');
        $I->see('Hello world!', '//h2');
        $I->see(
            "Hello and welcome to the blog. In this, the first post, I'll step you through what the blog is about and all the awesome things you're going to learn about by reading it.",
            '//p[@class="synopsis"]'
        );
        $I->see(
            "Welcome to the Slim Framework Markdown Blog. This is your first post. Edit or delete it, then start writing!",
            '//div[@id="content"]/p'
        );
        $I->see(
            "In this episode I have a fireside chat about what it’s like to live the life of a developer evangelist with Jack Skinner, otherwise known as @developerjack, whilst he was at the first BuzzConf. He talked with me about the crazy hours, random locations, shared some stories from the road, such as having a conference call whilst walking down the boarding gate to catch a flight.",
            '//div[@id="content"]/p'
        );
        $I->see('Matthew Setter', '//div/p/span[@id="author"]');
        $I->see('Jan 21, 2021', '//div/p/span[@id="publish-date"]');
        $I->see(
            '← Back to the home page',
            '//header/div/a[@id="back-to-home-page"]'
        );
        $I->seeNumberOfElements('//footer/p/a', 4);
        $I->see('© Matthew Setter.', '//footer/p');
        $I->seeLink('Impressum', '/impressum');
        $I->seeLink('Privacy Policy', '/privacy-policy');
        $I->seeLink('Terms of Use', '/terms-of-use');
        $I->seeLink('Disclaimer', '/disclaimer');
    }

    public function testClickingThePageLinks(AcceptanceTester $I)
    {
        $I->amOnPage('/item/hello-world');
        $I->seeResponseCodeIs(200);
        $I->click('← Back to the home page');
        $I->seeCurrentUrlEquals('/');
        $I->seeInTitle('Slim Framework Markdown Blog');

        $I->amOnPage('/item/hello-world');
        $I->seeResponseCodeIs(200);
        $I->click('Slim Framework Markdown Blog');
        $I->seeCurrentUrlEquals('/');
        $I->seeInTitle('Slim Framework Markdown Blog');
    }
}

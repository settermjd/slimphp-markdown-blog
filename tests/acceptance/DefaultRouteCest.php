<?php

class DefaultRouteCest
{
    public function _before(AcceptanceTester $I)
    {
    }

    // tests
    public function tryToTest(AcceptanceTester $I)
    {
        $I->amOnPage('/');
        $I->seeResponseCodeIs(200);
        $I->seeInTitle('Slim Framework Markdown Blog');
        $I->see('Slim Framework Markdown Blog', 'h1');
        $I->see('Items', 'h2');
        $I->seeElement('//ul', ['id' => 'blog-posts']);
        $I->see('© Matthew Setter.', '//footer/p');
        $I->seeLink('Impressum', '/impressum');
        $I->seeLink('Privacy Policy', '/privacy-policy');
        $I->seeLink('Terms of Use', '/terms-of-use');
        $I->seeLink('Disclaimer', '/disclaimer');
        $I->seeNumberOfElements('//ul[@id="blog-posts"]/li', 4);
        $I->seeNumberOfElements('//footer/p/a', 4);
    }
}

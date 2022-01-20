<?php

class ViewBlogPostRouteCest
{
    public function tryToTest(AcceptanceTester $I)
    {
        $I->amOnPage('/item/blogArticle-0011');
        $I->seeResponseCodeIs(200);
        $I->seeInTitle(
            'The Life of a Developer Evangelist, with Developer Jack | Slim Framework Markdown Blog'
        );
        $I->see(
            '← Back to the home page',
            '//header/div/a[@id="back-to-home-page"]'
        );
    }
}

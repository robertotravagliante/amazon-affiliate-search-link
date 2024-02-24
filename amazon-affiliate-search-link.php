<?php
namespace Grav\Plugin;

use Grav\Common\Plugin;
use RocketTheme\Toolbox\Event\Event;

class AmazonAffiliateSearchLinkPlugin extends Plugin
{
    public static function getSubscribedEvents()
    {
        return [
            'onPluginsInitialized' => ['onPluginsInitialized', 0],
        ];
    }

    public function onPluginsInitialized()
    {
        if ($this->isAdmin()) {
            return;
        }

        $this->enable([
            'onPageContentProcessed' => ['onPageContentProcessed', 0],
        ]);
    }

    public function onPageContentProcessed(Event $event)
    {
        $page = $event['page'];
        $config = $this->mergeConfig($page);
                
        // add a record into the Apache's log, with the value of the variable (for debugging purposes)
        // error_log('Value: ' . print_r($config->get('pageTypes'), true));

        // if the plugin is not enabled, then leave
        if (!$config->get('enabled')) {
            return;
        }

        // get the page types, if the user has specified only some type of pages, to show the affiliate links
        $pageTypes = $config->get('pageTypes', []);
        // if the user has not specified some types of pages, and the type of the current page is NOT among the selected ones
        if (!empty($pageTypes) && !in_array($page->template(), (array) $pageTypes)) {
            return;
        }

        // Get the list of the exception pages (where the links have not to be showed)
        $excludePages = $config->get('excludePages', []);
        // check if the current page is not into the exception list
        if (in_array($page->route(), $excludePages)) {
            // if this is an exception page, then leave
            return;
        }

        // Get the path for "amazon.png" image
        $imageUrl = $this->grav['base_url'] . '/user/plugins/' . $this->name . '/assets/amazon.png';

        $tagID = $config->get('tagID');
        $linkTemplate = $config->get('linkTemplate');
        $searchFullTitle = $config->get('searchFullTitle', true); // Default true, if not configured
        $searchTitleFromPost = $config->get('searchTitleFromPost');
        $paragraphsBeforeTopPositionLink = $config->get('paragraphsBeforeTopPositionLink');

        $searchKey = $searchFullTitle ? $page->title() : $this->extractCuriosityKeyword($page->content(), $page->title(), $searchTitleFromPost);

        // $affLink = sprintf($linkTemplate, urlencode($searchKey), $tagID);

        // link composition
        $affLink = $linkTemplate;
        $affLink = str_replace('[search_keywords]', urlencode($searchKey), $affLink);
        $affLink = str_replace('[tag_id]', $tagID, $affLink);
        // error_log('Value: ' . print_r($affLink, true));

        $positions = $config->get('position');
        $linkHTML = "<p align=\"center\"><a href=\"{$affLink}\" target=\"_blank\">Cerca $searchKey su Amazon <br /><img src=\"$imageUrl\" height=\"100px\" /></a></p>";

        $content = $this->insertAffiliateLink($page->getRawContent(), $linkHTML, $positions, $paragraphsBeforeTopPositionLink);
        $page->setRawContent($content);
    }


    private function extractCuriosityKeyword($content, $pageTitle, $searchTitleFromPost)
    {
        // Normalize the EOL to "\n", to avoid mistakes
        $normalizedContent = str_replace("\r\n", "\n", $content);
        $normalizedContent = str_replace("\r", "\n", $normalizedContent);
        $lines = explode("\n", $normalizedContent); // Divide the content into rows

        $searchTitleFromPostArray = explode('[content]', $searchTitleFromPost);
        $beforeString = $searchTitleFromPostArray[0];
        $afterString = $searchTitleFromPostArray[1];

        // record into the apache log
        // error_log('Value: ' . print_r($lines, true));

        foreach ($lines as $line) {
            // Remove the extra white spaces, at the beginning and at the end of the row
            $trimmedLine = trim($line);
            // Search for the desired pattern
            if (strpos($trimmedLine, $beforeString) === 0) {
                // Get the title
                $keyword = substr($trimmedLine, strlen($beforeString));
                $keyword = str_replace($afterString,'',$keyword);
                return trim($keyword);
            }
        }

        // If the desired pattern is not available, then use the entire title of the page, for searching on Amazon
        return trim($pageTitle);
    }


    private function insertAffiliateLink($content, $linkHTML, $positions, $paragraphsBeforeTopPositionLink = 1)
    {

        // record into the apache log
        // error_log('Positions: ' . print_r($positions, true));

        $paragraphs = explode('</p>', $content);
        $numParagraphs = count($paragraphs);

        if (($positions['top'] == 1) && ($numParagraphs > $paragraphsBeforeTopPositionLink)) {

            // error_log('paragraphsBeforeTopPositionLink: ' . print_r($paragraphsBeforeTopPositionLink, true));
            // error_log('numParagraphs: ' . print_r($numParagraphs, true));

            // Insert the top link after the specified number of paragraphs
            for ($i = 0; $i < $paragraphsBeforeTopPositionLink; $i++) {
                $paragraphs[$i] = $paragraphs[$i] . '</p>';
            }
            $paragraphs[$paragraphsBeforeTopPositionLink - 1] .= '<br>' . $linkHTML;
        } elseif ($positions['top'] == 1) {
            // If there aren't enough paragraphs, then insert the affiliate link after the first available paragraph
            $content = $linkHTML . "<br><br>" . $content;
        }
    
        if (($positions['middle'] == 1) && (count($paragraphs) > 1)) {
            // Get the central paragraph
            $middleParagraphIndex = floor((count($paragraphs) - 1) / 2);
            // Insert the affiliate link after the central paragraph
            $paragraphs[$middleParagraphIndex] .= '</p><br>' . $linkHTML;
        }
    
        if ($positions['bottom'] == 1) {
            // Insert the affiliate link at the end of the content
            $paragraphs[count($paragraphs) - 1] .= '<br>' . $linkHTML;
        }
    
        // Re-assemble the updated content
        $content = implode('', $paragraphs);
        return $content;
        
    }
}

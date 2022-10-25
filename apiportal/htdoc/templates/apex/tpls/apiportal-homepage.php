<?php
/**
 * ------------------------------------------------------------------------------
 * @package       T3 Framework for Joomla!
 * ------------------------------------------------------------------------------
 * @copyright     Copyright (C) 2004-2013 JoomlArt.com. All Rights Reserved.
 * @license       GNU General Public License version 2 or later; see LICENSE.txt
 * @authors       JoomlArt, JoomlaBamboo, (contribute to this project at github 
 *                & Google group to become co-author)
 * @Google group: https://groups.google.com/forum/#!forum/t3fw
 * @Link:         http://t3-framework.org 
 * ------------------------------------------------------------------------------
 */
$responsive = $this->getParam('responsive', 1);
$resClass = "";
if ($responsive == 0) {
    $resClass = "no-responsive";
}

defined('_JEXEC') or die;
?>

<!DOCTYPE html>
<html lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>"
      class='<jdoc:include type="pageclass" /> <?php echo $resClass ?>'>

    <head>
    <jdoc:include type="head" />
    <jdoc:include type="message" />
    <?php $this->loadBlock('head')  ?>
    <?php $this->addCss('layouts/blog') ?>
</head>

<body>
 
        <?php $this->loadBlock('api-header') ?>
    <div style="position: relative; z-index: 5;">
        <?php $this->loadBlock('spotlight-home1') ?>
    </div>    
    <main>
        <?php $this->loadBlock('banner') ?>
        <?php //$this->loadBlock('spotlight-home2') ?>
                
        <?php $this->loadBlock('spotlight-tiles') ?>
        <div style="position: relative; z-index: 5;">

            <?php $this->loadBlock('spotlight-home2')  ?>
        </div>
    </main>

        <?php //$this->loadBlock('mainbody')  ?>

        <?php //$this->loadBlock('spotlight-2')  ?>

        <?php //$this->loadBlock('spotlight-3')  ?>

        <?php $this->loadBlock('footer') ?>

    </div>

<script nonce="<?= $_SERVER["HTTP_CSP_NONCE"] ?>">
    function embedSVG(img) {
        var imgID = img.attr('id'),
            imgClass = img.attr('class'),
            imgURL = img.attr('src'),
            imgGet = jQuery.get(imgURL);

        jQuery.when(imgGet).done(function (data) {
            var svg = jQuery(data).find('svg');

            if (typeof imgID !== 'undefined') {
                svg.attr('id', imgID);
            }

            if (typeof imgClass !== 'undefined') {
                svg = svg.attr('class', imgClass + ' replaced-svg');
            }

            img.replaceWith(svg);
        }).fail(function () {
            console.log("Failed to convert SVG.");
        });
    }

    jQuery('main').find('img.svg').each(function () {
        embedSVG(jQuery(this));
    });
</script>
</body>
</html>
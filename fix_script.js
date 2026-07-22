const fs = require('fs');
let code = fs.readFileSync('functions-snippet-complete.php', 'utf8');

const targetFunction = `function render_munisys_insight_detail_shortcode($atts, $content = null)
{
    $a = shortcode_atts(array(
        'title' => 'PRESS RELEASE',
        'date' => 'Monday 08 December 2025',
        'image' => '/wp-content/uploads/datacenter.png'
    ), $atts);

    ob_start();
    ?>`;

const replacement = `function render_munisys_insight_detail_shortcode($atts, $content = null)
{
    global $post;
    $slug = $post ? $post->post_name : '';
    
    $title = '';
    $date = '';
    $image = '';
    $html_content = '';

    if ($slug === 'press-release') {
        $title = 'PRESS RELEASE';
        $date = '08 December 2025';
        $image = '/wp-content/uploads/datacenter.png';
        $html_content = '<p align="center"><b>Helios Investment Partners and Fipar-Holding, Launch Morocco\\'s Leading Digital Infrastructure Platform with the Acquisition of Maroc Datacenter, Munisys and Medasys</b></p>
<p><b>[08 December 2025].</b> Helios Investment Partners ("Helios", acting on behalf of funds it advises), the leading Africa-focused private investment firm, and Fipar-Holding, a subsidiary of CDG Invest, are pleased to announce the creation of 3MDC, a new digital infrastructure platform. This platform aims to accelerate Morocco\\'s digital transformation and strengthen its position as a regional technology hub.</p>
<p>The new platform brings together Maroc Datacenter (MDC), Munisys, and Medasys, three of Morocco\\'s most established players in cloud, data, cybersecurity and digital services. Their combination creates a unique, fully integrated hybrid-cloud platform serving enterprise and public-sector clients across Morocco and Southern Europe.</p>
<p>The new platform leverages MDC\\'s fast-growing carrier-neutral Tier III datacenter together with Munisys\\' longstanding expertise in on-premises cloud, cybersecurity, systems integration and digital solutions. Medasys adds strong complementary technological capabilities and a deep understanding of the Moroccan IT landscape.</p>';
    } elseif ($slug === 'new-headquarters') {
        $title = 'New headquarters';
        $date = 'Mai 2024';
        $image = '/wp-content/uploads/datacenter.png';
        $html_content = '<p>Munisys is excited to announce that in a few days our headquarters in Casablanca are going to move to our newly built address in the heart of the Green City of Bouskoura.</p>';
    } elseif ($slug === 'a-new-visual-identity-for-munisys') {
        $title = 'A new visual identity for Munisys';
        $date = 'Janvier 2024';
        $image = '';
        $html_content = '<p>After more than 30 years of innovation, commitment and excellence shared with our valued clients, it is time to adopt a new visual identity, to carry our values, mark our singularities, and translate our ambitions.</p>';
    } elseif ($slug === 'gitex-africa') {
        $title = 'Come and visit us at Gitex Africa';
        $date = '31 May 2023';
        $image = '';
        $html_content = '<p>Munisys is exhibiting at Gitex Africa 2023, happening in Marrakech from 31 May to 02 June. This first edition is a tremendous opportunity to showcase the immense potential and capacity of the African digital ecosystem.</p>';
    } else {
        $a = shortcode_atts(array(
            'title' => 'PRESS RELEASE',
            'date' => 'Monday 08 December 2025',
            'image' => '/wp-content/uploads/datacenter.png'
        ), $atts);
        $title = $a['title'];
        $date = $a['date'];
        $image = $a['image'];
        $html_content = do_shortcode($content);
    }

    ob_start();
    ?>`;

code = code.replace(targetFunction, replacement);

const targetHtml1 = `<span class="pr-date"><?php echo esc_html($a['date']); ?></span>
        <h2 class="pr-title"><?php echo esc_html($a['title']); ?></h2>
        
        <?php if (!empty($a['image'])) : ?>
            <img class="pr-image" src="<?php echo esc_url($a['image']); ?>" alt="Insight Image">
        <?php endif; ?>
        
        <div class="pr-body">
          <?php echo do_shortcode($content); ?>
        </div>`;

const replacementHtml1 = `<span class="pr-date"><?php echo esc_html($date); ?></span>
        <h2 class="pr-title"><?php echo esc_html($title); ?></h2>
        
        <?php if (!empty($image)) : ?>
            <img class="pr-image" src="<?php echo esc_url($image); ?>" alt="Insight Image">
        <?php endif; ?>
        
        <div class="pr-body">
          <?php echo $html_content; ?>
        </div>`;

code = code.replace(targetHtml1, replacementHtml1);

fs.writeFileSync('functions-snippet-complete.php', code);
console.log('Script done');

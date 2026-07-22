const fs = require('fs');

let php = fs.readFileSync('functions-snippet-complete.php', 'utf8');

// 1. Change default links in munisys_insights shortcode to point to local URLs instead of absolute ones
php = php.replace(/'https:\/\/www\.munisys\.ma\/en\/insights\/view\/press-release-page'/g, "'/press-release/'");
php = php.replace(/'https:\/\/www\.munisys\.ma\/en\/insights\/view\/new-headquarters'/g, "'/new-headquarters/'");
php = php.replace(/'https:\/\/www\.munisys\.ma\/en\/insights\/view\/a-new-visual-identity-for-munisys'/g, "'/a-new-visual-identity-for-munisys/'");
php = php.replace(/'https:\/\/www\.munisys\.ma\/en\/insights\/view\/come-and-visit-us-at-gitex-africa'/g, "'/gitex-africa/'");

// 2. Add the [munisys_insight_detail] shortcode at the end
const shortcodeCode = `
// ==========================================
// 13. SHORTCODE: MUNISYS INSIGHT DETAIL PAGE
// ==========================================
add_shortcode('munisys_insight_detail', 'render_munisys_insight_detail_shortcode');
function render_munisys_insight_detail_shortcode($atts, $content = null)
{
    $a = shortcode_atts(array(
        'title' => 'PRESS RELEASE',
        'date' => 'Monday 08 December 2025',
        'image' => '/wp-content/uploads/datacenter.png'
    ), $atts);

    ob_start();
    ?>
    <style>
    @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800;900&display=swap');
    #munisys-wrapper {
        font-family: 'Montserrat', sans-serif !important;
        background: #ffffff;
        color: #333333;
        -webkit-font-smoothing: antialiased;
        width: 100vw !important;
        position: relative;
        left: 50% !important;
        right: 50% !important;
        margin-left: -50vw !important;
    }
    #munisys-wrapper .pr-banner {
        background: #1d4e79;
        color: #fff;
        padding: 6rem 10%;
        text-align: left;
    }
    #munisys-wrapper .pr-banner h1 {
        font-size: 3.5rem;
        font-weight: 800;
        margin: 0;
        letter-spacing: -1px;
    }
    #munisys-wrapper .pr-content {
        padding: 5rem 10%;
        max-width: 1000px;
        margin: 0 auto;
    }
    #munisys-wrapper .pr-date {
        display: block;
        color: #922b55;
        font-weight: 600;
        font-size: 0.9rem;
        margin-bottom: 1rem;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    #munisys-wrapper .pr-title {
        font-size: 2.5rem;
        font-weight: 800;
        color: #1d4e79;
        margin-top: 0;
        margin-bottom: 3rem;
        line-height: 1.2;
        letter-spacing: -1px;
    }
    #munisys-wrapper .pr-image {
        width: 100%;
        height: auto;
        border-radius: 8px;
        margin-bottom: 3rem;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    }
    #munisys-wrapper .pr-body {
        font-size: 1.1rem;
        line-height: 1.8;
        color: #555;
    }
    #munisys-wrapper .pr-body p {
        margin-bottom: 1.5rem;
    }
    #munisys-wrapper .pr-body b {
        color: #1d4e79;
    }
    @media(max-width:768px) {
        #munisys-wrapper .pr-banner { padding: 4rem 5%; }
        #munisys-wrapper .pr-banner h1 { font-size: 2.5rem; }
        #munisys-wrapper .pr-content { padding: 3rem 5%; }
        #munisys-wrapper .pr-title { font-size: 2rem; }
    }
    </style>

    <div id="munisys-wrapper">
      <!-- BANNER -->
      <div class="pr-banner">
        <h1>Insights</h1>
      </div>

      <!-- ARTICLE CONTENT -->
      <div class="pr-content">
        <span class="pr-date"><?php echo esc_html($a['date']); ?></span>
        <h2 class="pr-title"><?php echo esc_html($a['title']); ?></h2>
        
        <?php if (!empty($a['image'])) : ?>
            <img class="pr-image" src="<?php echo esc_url($a['image']); ?>" alt="Insight Image">
        <?php endif; ?>
        
        <div class="pr-body">
          <?php echo do_shortcode($content); ?>
        </div>
      </div>
    </div>
    <?php
    return ob_get_clean();
}
`;

if (!php.includes('munisys_insight_detail')) {
    // Find the end of the file
    php = php.trim();
    if (php.endsWith('?>')) {
        php = php.substring(0, php.length - 2);
    }
    php += "\n" + shortcodeCode + "\n?>\n";
}

fs.writeFileSync('functions-snippet-complete.php', php);
console.log('Done!');

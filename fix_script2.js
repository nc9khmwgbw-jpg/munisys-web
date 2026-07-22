const fs = require('fs');
let code = fs.readFileSync('functions-snippet-complete.php', 'utf8');

const targetFunctionStart = `function render_munisys_insight_detail_shortcode($atts, $content = null)`;
const targetFunctionEnd = `return ob_get_clean();
}`;

let startIdx = code.indexOf(targetFunctionStart);
let endIdx = code.indexOf(targetFunctionEnd, startIdx) + targetFunctionEnd.length;

let oldFunction = code.substring(startIdx, endIdx);

const replacement = `function render_munisys_insight_detail_shortcode($atts, $content = null)
{
    global $post;
    $slug = $post ? $post->post_name : '';
    
    // Si on est dans Breakdance preview, on peut utiliser des attributs pour forcer la page
    $a = shortcode_atts(array(
        'id' => ''
    ), $atts);
    
    if (!empty($a['id'])) {
        $slug = $a['id'];
    }

    $title = '';
    $date = '';
    $image = '';
    $html_content = '';

    if (strpos($slug, 'press-release') !== false) {
        $title = 'PRESS RELEASE';
        $date = '08 December 2025';
        $image = '/wp-content/uploads/datacenter.png';
        $html_content = '<p align="center"><b>Helios Investment Partners and Fipar-Holding, Launch Morocco\\'s Leading Digital Infrastructure Platform with the Acquisition of Maroc Datacenter, Munisys and Medasys</b></p>
<p><b>[08 December 2025].</b> Helios Investment Partners ("Helios", acting on behalf of funds it advises), the leading Africa-focused private investment firm, and Fipar-Holding, a subsidiary of CDG Invest, are pleased to announce the creation of 3MDC, a new digital infrastructure platform. This platform aims to accelerate Morocco\\'s digital transformation and strengthen its position as a regional technology hub.</p>
<p>The new platform brings together Maroc Datacenter (MDC), Munisys, and Medasys, three of Morocco\\'s most established players in cloud, data, cybersecurity and digital services. Their combination creates a unique, fully integrated hybrid-cloud platform serving enterprise and public-sector clients across Morocco and Southern Europe.</p>
<p>The new platform leverages MDC\\'s fast-growing carrier-neutral Tier III datacenter together with Munisys\\' longstanding expertise in on-premises cloud, cybersecurity, systems integration and digital solutions. Medasys adds strong complementary technological capabilities and a deep understanding of the Moroccan IT landscape.</p>';
    } elseif (strpos($slug, 'new-headquarters') !== false) {
        $title = 'New headquarters';
        $date = '12 May 2023';
        $image = 'https://www.munisys.ma/uploads/actualites/27ddbef45a9b70352bcd44a4eb7aee76f0258295.png';
        $html_content = '<p>Munisys is excited to announce that in a few days our headquarters in Casablanca are going to move to our newly built address in the heart of the Green City of Bouskoura.</p>
<p>Easily accessible and surrounded by forest and greenery, these new offices will provide our customers, partners and Munisys\\' teams the ideal environment for thinking, innovating, working and sharing. With about 2000 square meters of office space, everything has been designed to align with our values and culture and to achieve the right balance between privacy and connection, enclosure and perspective, serenity and energy, thus efficiently supporting our daily mission to transform great ideas into successful projects.</p>
<p>We hope we can welcome you soon in our new headquarters!</p>';
    } elseif (strpos($slug, 'a-new-visual-identity-for-munisys') !== false) {
        $title = 'A new visual identity for Munisys';
        $date = '31 May 2023';
        $image = 'https://www.munisys.ma/uploads/actualites/718cff8dc6ac06408b39c9f129b20e33c44d7d10.png';
        $html_content = '<p>After more than 30 years of innovation, commitment and excellence shared with our valued clients, it is time to adopt a new visual identity, to carry our values, mark our singularities, and translate our ambitions.</p>
<p>Starting from 31 May 2023, we will wear new colors and use a new logo and typography to better reflect who we are.</p>
<p>This (r)evolution will have an impact on how we are seen from the outside, but only reinforce what makes the essence of Munisys, our culture and how we serve our clients: integrity, excellence and long-term thinking. This is what makes us different and recognizable</p>';
    } elseif (strpos($slug, 'gitex-africa') !== false) {
        $title = 'Come and visit us at Gitex Africa';
        $date = '31 May 2023';
        $image = 'https://www.munisys.ma/uploads/actualites/612051b523703aefc2cb0fcaee22afe42f93eb2d.png';
        $html_content = '<p>Munisys is exhibiting at Gitex Africa 2023, happening in Marrakech from 31 May to 02 June. This first edition is a tremendous opportunity to showcase the immense potential and capacity of the African digital ecosystem, to learn about the infinite possibilities offered when technology meets human skills and creativity, and to share insights about how digital can contribute to elevate our countries and continent.</p>
<p>Come and visit us on booth 2A-5 where we will be sharing the latest IT infrastructure technology solutions and services we offer to help you deploy innovative and value added digital services</p>';
    } else {
        $title = 'Insight Details';
        $date = '...';
        $image = '';
        $html_content = '<p>Veuillez vérifier le permalien de la page. Le permalien doit contenir "press-release", "new-headquarters", "a-new-visual-identity-for-munisys" ou "gitex-africa". 
        <br>Sinon, vous pouvez forcer le contenu en tapant par exemple : [munisys_insight_detail id="new-headquarters"]</p>';
    }

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
        <span class="pr-date"><?php echo esc_html($date); ?></span>
        <h2 class="pr-title"><?php echo esc_html($title); ?></h2>
        
        <?php if (!empty($image)) : ?>
            <img class="pr-image" src="<?php echo esc_url($image); ?>" alt="Insight Image">
        <?php endif; ?>
        
        <div class="pr-body">
          <?php echo $html_content; ?>
        </div>
      </div>
    </div>
    <?php
    return ob_get_clean();
}`;

code = code.replace(oldFunction, replacement);
fs.writeFileSync('functions-snippet-complete.php', code);
console.log('Script done');

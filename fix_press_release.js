const fs = require('fs');
let code = fs.readFileSync('functions-snippet-complete.php', 'utf8');

const targetHtml = `'<p align="center"><b>Helios Investment Partners and Fipar-Holding, Launch Morocco\\'s Leading Digital Infrastructure Platform with the Acquisition of Maroc Datacenter, Munisys and Medasys</b></p>
<p><b>[08 December 2025].</b> Helios Investment Partners ("Helios", acting on behalf of funds it advises), the leading Africa-focused private investment firm, and Fipar-Holding, a subsidiary of CDG Invest, are pleased to announce the creation of 3MDC, a new digital infrastructure platform. This platform aims to accelerate Morocco\\'s digital transformation and strengthen its position as a regional technology hub.</p>
<p>The new platform brings together Maroc Datacenter (MDC), Munisys, and Medasys, three of Morocco\\'s most established players in cloud, data, cybersecurity and digital services. Their combination creates a unique, fully integrated hybrid-cloud platform serving enterprise and public-sector clients across Morocco and Southern Europe.</p>
<p>The new platform leverages MDC\\'s fast-growing carrier-neutral Tier III datacenter together with Munisys\\' longstanding expertise in on-premises cloud, cybersecurity, systems integration and digital solutions. Medasys adds strong complementary technological capabilities and a deep understanding of the Moroccan IT landscape.</p>'`;

const replacementHtml = `'<p align="center"><b>Helios Investment Partners and Fipar-Holding, Launch Morocco\\'s Leading Digital Infrastructure Platform with the Acquisition of Maroc Datacenter, Munisys and Medasys</b></p>
<p><b>[08 December 2025].</b> Helios Investment Partners ("Helios", acting on behalf of funds it advises), the leading Africa-focused private investment firm, and Fipar-Holding, a subsidiary of CDG Invest, are pleased to announce the creation of 3MDC, a new digital infrastructure platform. This platform aims to accelerate Morocco\\'s digital transformation and strengthen its position as a regional technology hub.</p>
<p>The new platform brings together Maroc Datacenter (MDC), Munisys, and Medasys, three of Morocco\\'s most established players in cloud, data, cybersecurity and digital services. Their combination creates a unique, fully integrated hybrid-cloud platform serving enterprise and public-sector clients across Morocco and Southern Europe.</p>
<p>The new platform leverages MDC\\'s fast-growing carrier-neutral Tier III datacenter together with Munisys\\' longstanding expertise in on-premises cloud, cybersecurity, systems integration and digital solutions. Medasys adds strong complementary technological capabilities and a deep understanding of the Moroccan IT landscape.</p>
<p>Through strategic partnerships with several world-leading technology providers, the platform will offer advanced, secure and sovereign digital solutions tailored to the evolving needs of organizations.</p>
<p>3MDC company will be jointly owned by Helios IV, which will hold 44%, Fipar, which will hold 40%, and the founders and management teams, who will collectively hold 16%. This balanced ownership structure underscores the strong alignment between international investment expertise and national institutional support and ensures strong local ownership and alignment with Morocco\\'s institutional ecosystem.</p>
<p>Helios has a proven track record of delivering large-scale digital infrastructure projects across Africa. Through this partnership, Morocco will gain greater digital security and self-reliance by expanding critical in-country capacity. Helios and Fipar are united in their vision to build scalable, future-ready digital platforms, with Fipar providing deep local knowledge and close ties to Moroccan institutions, and Helios bringing extensive expertise in digital infrastructure and a long-term investment perspective.</p>
<p><b>Babatunde Soyoye, Co-Founder and Managing Partner, Helios Investment Partners, commented:</b> <i>"This investment builds on our conviction in Africa\\'s digital future and our track record of creating national champions in partnership with leading local institutions. The combination of Maroc Datacenter, Munisys and Medasys creates a unique Moroccan platform with the scale and expertise to enable secure, sovereign and sustainable digital growth."</i></p>
<p><b>Khalid Ziane, General Manager of Fipar, said:</b> <i>"This partnership reflects our ambition to strengthen Morocco\\'s technological foundations and support the emergence of a modern, resilient and inclusive digital ecosystem. The new platform will play a pivotal role in accelerating the country\\'s digital transition."</i></p>
<p><b>Younes Peter Amine, CEO of Munisys, commented:</b> <i>"Helios and Fipar\\'s backing will allow us to expand our cloud and cybersecurity offerings while enhancing the breadth and reliability of services our customers rely on. Together with MDC and Medasys, we will deliver a truly end-to-end, locally hosted and secure platform for Morocco\\'s digital economy."</i></p>
<p><b>Abderrahmane Mounir, President and CEO of Maroc Datacenter, added:</b> <i>"Becoming part of 3MDC is an important step in MDC\\'s evolution, and we look forward to help build Morocco\\'s leading digital infrastructure group and support the country\\'s digital transformation."</i></p>
<p align="center"><b>ENDS</b></p>
<p><b>For further information please contact:</b></p>
<p><b>For Helios:</b><br>
Teneo<br>
<a href="mailto:heliosmedia@teneo.com">heliosmedia@teneo.com</a><br>
+44 (0)20 7260 2700</p>
<p><b>For Fipar-Holding:</b><br>
Salma Benjalloun<br>
<a href="mailto:salma.benjalloun@cdginvest.ma">salma.benjalloun@cdginvest.ma</a><br>
+212 6 61 50 51 24</p>
<p><b>About Helios Investment Partners</b><br>
Established in 2004, Helios Investment Partners is the largest Africa-focused private investment firm, with a record that spans creating start-ups to providing expanding companies with growth capital and expertise. The firm is led and managed by a predominantly African team based in London, Paris, Lagos and Nairobi, with the language skills and cultural affinity to engage with local entrepreneurs, managers and intermediaries on the continent.</p>
<p><b>About Fipar-Holding</b><br>
Fipar-Holding is an investment firm owned by CDG Invest, the investment arm of the Caisse de Dépôt et de Gestion (CDG) Group. As a proactive and committed minority financial investor, Fipar-Holding strives to initiate or accelerate the growth and development of major projects in Morocco. Its mission is to support both private and public Moroccan companies in becoming key players domestically and internationally.</p>
<p><b>About Munisys</b><br>
As a premier digital infrastructure integrator, Munisys empowers public and private enterprises throughout Morocco. With over three decades of expertise, the company has cultivated a unique skill set in deploying and managing private and hybrid infrastructure environments. Munisys supports its clients through the entire lifecycle of their digital assets—from design and deployment to evolution and operations—covering the spectrum from critical core architectures to end-user solutions.</p>'`;

code = code.replace(targetHtml, replacementHtml);
fs.writeFileSync('functions-snippet-complete.php', code);
console.log('Done');

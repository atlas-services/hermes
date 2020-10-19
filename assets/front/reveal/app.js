// /*
//     css
//  */
// import '../css/hermes/base.css';
// import '../css/navbar.css';
import '../../css/base.css';
import '../css/hermes/app.css';
import 'reveal.js/dist/reveal.css';
import 'reveal.js/dist/theme/black.css';
import 'reveal.js/plugin/highlight/monokai.css';
import 'reveal.js-menu/menu.css';
import '../css/hermes/reveal.css';

// /*
//     js
//  */
import Reveal from 'reveal.js';
import Markdown from 'reveal.js/plugin/markdown/markdown.esm';
import RevealHighlight from 'reveal.js/plugin/highlight/highlight.esm';
import RevealMenu from 'reveal.js-menu/menu';

let deck = new Reveal({
    plugins: [ Markdown, RevealHighlight, RevealMenu ]
})
deck.initialize({ autoSlide: 3000 });
deck.initialize({ slideNumber: 'c/t' });
deck.initialize({ autoPlayMedia: true })




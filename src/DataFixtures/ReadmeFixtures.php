<?php

namespace App\DataFixtures;


use App\Entity\Readme;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
//use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Persistence\ObjectManager;

class ReadmeFixtures extends Fixture implements FixtureGroupInterface, DependentFixtureInterface
{

    private $readme = '
 <div class="wpb_content_element wpb_text_column">
<div class="wpb_wrapper">
<p>Afin d&#39;enrichir au mieux les pages de votre site, il est important de comprendre les informations que vous saisissez et dans quel ordre les saisir.</p>

<p>Ce document va vous aider &agrave; cr&eacute;er un menu, compos&eacute; par un en t&ecirc;te de menu (<span style="font-size:12px"><em><a href="http://127.0.0.1:8000/admin/?entity=Sheet&amp;action=list&amp;menuIndex=5&amp;submenuIndex=-1" target="_blank"><strong><span style="font-family:Verdana,Geneva,sans-serif">Page</span></strong></a></em></span>) et des sous menus (<span style="font-size:12px"><em><a href="http://127.0.0.1:8000/admin/?entity=Menu&amp;action=list&amp;menuIndex=5&amp;submenuIndex=-1" target="_blank"><strong><span style="font-family:Verdana,Geneva,sans-serif">Menu</span></strong></a></em></span>)<br />
A cette &eacute;tape, vous pourrez constater que votre site Web s&#39;est d&eacute;ja enrichi au niveau du menu.</p>

<p>Pas mal!</p>

<p>Mais un menu sans un contenu &agrave; afficher, cela ne sert pas &agrave; grand chose.</p>

<p>Il faut donc cr&eacute;er du contenu(<span style="font-size:12px"><em><strong><a href="http://127.0.0.1:8000/admin/?entity=Post&amp;action=list&amp;menuIndex=5&amp;submenuIndex=-1" target="_blank"><span style="font-family:Verdana,Geneva,sans-serif">Contenu</span></a></strong></em></span>) et choisir quelle pr&eacute;sentation(<span style="font-size:12px"><em><a href="http://127.0.0.1:8000/admin/?entity=Section&amp;action=list&amp;menuIndex=5&amp;submenuIndex=-1" target="_blank"><strong><span style="font-family:Verdana,Geneva,sans-serif">Section</span></strong></a></em></span>) aura ce contenu.</p>
&nbsp;

<p><span style="font-size:14px"><strong>Mais assez tard&eacute;,commen&ccedil;ons par cr&eacute;er notre premier menu :</strong></span></p>

<p>Et si nous ajoutions un menu &quot;catalogue<em>&quot;</em> avec une rubrique &quot;enfant&quot; et une rubrique &quot;adulte&quot;?</p>

<p>Rien de plus simple que d&#39;ajouter le menu catalogue : il faut aller sur la page <span style="font-size:12px"><em><a href="http://127.0.0.1:8000/admin/?entity=Sheet&amp;action=list&amp;menuIndex=5&amp;submenuIndex=-1" target="_blank"><strong><span style="font-family:Verdana,Geneva,sans-serif">Page</span></strong></a></em></span> et cr&eacute;er une page &quot;catalogue&quot;, sans oublier de l&#39;activer et d&#39;indiquer sa position.Il n&#39;est pour le moment pas n&eacute;cessaire d&#39;y ajouter une image (de fond).</p>

<p>C&#39;est fait? Alors on peut aller voir le r&eacute;sultat sur le site...le menu s&#39;est enrichi avec notre ajout!</p>

<p>On&nbsp; peut continuer.</p>

<p>On va y ajouter tr&egrave;s simplement un sous menu &quot;enfant&quot; en indiquant bien sur quelle page nous souhatons l&#39;ajouter...par ici : <span style="font-size:12px"><em><a href="http://127.0.0.1:8000/admin/?entity=Menu&amp;action=list&amp;menuIndex=5&amp;submenuIndex=-1" target="_blank"><strong><span style="font-family:Verdana,Geneva,sans-serif">Menu</span></strong></a></em></span> .</p>

<p>On n&#39;oubliera pas de l&#39;activer, d&#39;indiquer sa position ainsi que &agrave; la Page du menu auquel il est associ&eacute; (&quot;catalogue&quot;, voyons!).</p>

<p>On proc&eacute;dera de m&ecirc;me pour cr&eacute;er un sous menu &quot;adulte&quot; (par ici : <span style="font-size:12px"><em><a href="http://127.0.0.1:8000/admin/?entity=Menu&amp;action=list&amp;menuIndex=5&amp;submenuIndex=-1" target="_blank"><strong><span style="font-family:Verdana,Geneva,sans-serif">Menu</span></strong></a></em></span> ).</p>

<p><span style="font-size:14px"><strong>Vous savez ajouter des &eacute;l&eacute;ments dans le menu, passons au contenu :</strong></span></p>

<p>Pour ajouter du contenu, c&#39;est tr&egrave;s simple.mais avant de l&#39;ajouter, il faut choisir une pr&eacute;sentation pour ce contenu =====&gt; il faut cr&eacute;er une section pour le contenu.</p>

<p>Donc pour le menu enfant, nous allons ajouter un portfolio enfant avec 4 images et des descriptions par exemples.</p>

<p>Comment faire?</p>

<ol>
	<li>cr&eacute;er une section &quot;enfant&quot; (<span style="font-size:12px"><em><a href="http://127.0.0.1:8000/admin/?entity=Section&amp;action=list&amp;menuIndex=5&amp;submenuIndex=-1" target="_blank"><strong><span style="font-family:Verdana,Geneva,sans-serif">Section)</span></strong></a></em></span> avec pour template &quot;folio&quot;. (c&#39;est fini pour la section).</li>
	<li>Cr&eacute;er les 4 contenus pour le catalogue enfant :&nbsp;<span style="font-size:12px"><em><strong><a href="http://127.0.0.1:8000/admin/?entity=Post&amp;action=list&amp;menuIndex=5&amp;submenuIndex=-1" target="_blank"><span style="font-family:Verdana,Geneva,sans-serif">Contenu</span></a></strong></em></span> . Ne faire oublier de bien indiquer la &quot;Section du post&quot; cr&eacute;&eacute;e &agrave; l&#39;&eacute;tape pr&eacute;c&eacute;dente, &agrave; savoir, la section &quot;enfant&quot;.Ici, il fadra mettre du contenu et une image par contenu.</li>
	<li>Et pour que nos contenu soient bien visible, il faudra retourner sur le menu &quot;enfant&quot; (<span style="font-size:12px"><em><a href="http://127.0.0.1:8000/admin/?entity=Menu&amp;action=list&amp;menuIndex=5&amp;submenuIndex=-1" target="_blank"><strong><span style="font-family:Verdana,Geneva,sans-serif">Menu</span></strong></a></em></span>) et tout en bas, indiquer la section &quot;enfant&quot; cr&eacute;e &agrave; l&#39;&eacute;tape 1 de cette partie.</li>
</ol>

<p>Des questions?</p>

<p>&nbsp;</p>
</div>
</div>
    ';

    public function load(ObjectManager $manager)
    {
        $item = new Readme();

        $item->setActive(true);
        $item->setName("Readme");
        $item->setSummary("readme pour l'administrateur du site");
        $item->setContent($this->readme);

        $manager->persist($item);
        $manager->flush();

    }

    public static function getGroups(): array
    {
        return ['readme'];
    }

    public function getDependencies()
    {
        return array(
            UserFixtures::class,
        );
    }

}

<?php
namespace App\Command;

use App\Entity\Config\Config;
use App\Entity\Hermes\Menu;
use App\Entity\Hermes\Post;
use App\Entity\Hermes\Section;
use App\Entity\Hermes\Sheet;
use App\Entity\Hermes\Template;
use App\Entity\Hermes\User;
use App\Service\Onepage;
use App\Service\TemplateLibreHermes;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class HermesDbCommand extends Command
{
    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'hermes:db-update';

    protected $configurations;
    protected $em;
    protected $emConfig;
    protected $locale;

    protected $app_name;
    protected $onepage;

    public function __construct(ManagerRegistry $doctrine,ParameterBagInterface $container,  Onepage $onepage)
    {
        $this->em = $doctrine->getManager('default');
        $this->emConfig = $doctrine->getManager('config');
        $this->onepage = $onepage;
        $this->configurations = $container->get('init');
        $this->locale = $container->get('app.default_locale');
        $this->app_name = $container->get('app.name');

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName(self::$defaultName)
            // the short description shown while running "php bin/console list"
            ->setDescription('Add, if not in database, users, templates and config defined in hermes.yaml.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // ... put here the code to run in your command
        try {
            $dbuser = $this->em
                ->getRepository(User::class)
                ->findAll();
            $dbconfig = $this->emConfig
                ->getRepository(Config::class)
                ->findAll();
            $dbtemplate = $this->em
                ->getRepository(Template::class)
                ->findAll();
            $this->newConfig($this->configurations, $dbuser, $dbconfig,$dbtemplate);
        }catch (\Exception $e){
            echo $e->getMessage();
            echo $e->getTraceAsString();
        }


        // outputs a message without adding a "\n" at the end of the line
        $output->writeln('database updated ');
        // this method must return an integer number with the "exit status code"
        // of the command. You can also use these constants to make code more readable

        // return this if there was no problem running the command
        // (it's equivalent to returning int(0))
        return Command::SUCCESS;

        // or return this if some error happened during the execution
        // (it's equivalent to returning int(1))
        // return Command::FAILURE;
    }

    private function newConfig($configurations, $dbuser,$dbconfig,$dbtemplate){
        $dbuseremail = array_column($dbuser, 'email');
        $dbconfigcode = array_column($dbconfig, 'code');
        $dbtemplatecode = array_column($dbtemplate, 'code');


        // remove old config si absente de hermes.yaml
        $config_yaml = [];
        foreach($configurations['config']as $configuration){
            $config_yaml = array_merge($config_yaml, array_keys($configuration));
        }

        $config_diff = array_diff($dbconfigcode,$config_yaml);

        foreach($config_diff as  $code){
            $config_remove = $this->emConfig
                ->getRepository(Config::class)
                ->findOneBy(['code' => $code]);
            $this->emConfig->remove($config_remove);
        }


        // remove old templates si absente de hermes.yaml
        $template_yaml = array_keys($configurations['template']);
        $template_diff = array_diff($dbtemplatecode,$template_yaml);

        foreach($template_diff as  $code){
            $template_remove = $this->em
                ->getRepository(Template::class)
                ->findOneBy(['code' => $code]);
            $this->em->remove($template_remove);
        }


        foreach ($configurations as $key=>$value){
            if('user' == $key){
                foreach ($value as $type=>$configuration){
                    foreach ($configuration as $code=>$conf){
                        if('email' == $code){
                            if(!in_array($conf, $dbuseremail)){
                                $user = new User();
                                $user->setFirstname($configuration['firstname']);
                                $user->setLastname($configuration['lastname']);
                                $user->setEmail($configuration['email']);
                                $user->setPassword($configuration['password']);
                                $user->setRoles([$configuration['roles']]);
                                $this->em->persist($user);
                            }
                        }
                    }
                }
            }
            if('config' == $key){
                foreach ($value as $type=>$configuration){
                    foreach ($configuration as $code=>$conf){
                        if(!in_array($code, $dbconfigcode)){
                            $config = new Config();
                            $config->setType($type);
                            $config->setCode($code);
                            $config->setSummary($conf['summary']);
                            $config->setValue($conf['value']);
                            $this->emConfig->persist($config);
                        }
                    }
                }
            }
            if('template' == $key){
                foreach ($value as $code=>$conf){
                    if(!in_array($code, $dbtemplatecode)){
                        $template = new Template();
                    }else{
                        $template = $this->em->getRepository(Template::class)
                        ->findOneBy(['code' => $code]);
                    }
                    $template->setCode($code);
                    $template->setType($conf['type']);
                    $template->setSummary($conf['summary']);
                    $template->setName($conf['name']);
                    $this->em->persist($template);
                } 
            }

            if(TemplateLibreHermes::TEMPLATE_LIBRE_HERMES == $key){
                foreach ($value as $key=>$modele){
                    foreach ($modele as $kcode=>$conf){
                        $code = $key.'-'.$kcode;
                        if(!in_array($kcode, $dbtemplatecode)){
                            $template = new Template();
                            $template->setCode($code);
                            $template->setSummary($conf['summary']);
                            $template->setName($conf['name']);
                            $this->em->persist($template);
                        }
                    }
                }
            }

        }
        $this->em->flush();
        $this->emConfig->flush();
        $this->initPage('libre', $this->locale);
    }

    private function initPage($code, $locale){

        $localeSheet = $this->em->getRepository(Sheet::class)->findOneBy(['locale' => $locale]);
        if(empty($localeSheet)){
            $sheet = new Sheet();
            $sheet->setLocale($this->locale);
            $sheet->setName("Accueil");
            $sheet->setReferenceName("Accueil");
            $sheet->setSlug("Accueil");
            $this->em->persist($sheet);
            $this->em->flush();
            $menu = new Menu();
            $menu->setLocale($this->locale);
            $menu->setSheet($sheet);
            $menu->setName("Accueil");
            $menu->setReferenceName("accueil");
            $menu->setSlug("Accueil");
            $this->em->persist($menu);
            $this->em->flush();
            $template = $this->em
                ->getRepository(Template::class)
                ->findOneBy(['code' => $code]);
            $section = new Section();
            $section->setMenu($menu);
            $section->setTemplate($template);
            $section->setName("Accueil");
            $this->em->persist($section);
            $this->em->flush();
            $post= new Post();
            $post->setName("Accueil");
            $post->setSection($section);
            $post->setContent("<div class='col-12 col-sm-9 mx-auto my-5 py-5 h-100 card border-2 border-dark rounded'><p class='col-12 my-5 pY-5 h3 text-center'>Bienvenue sur la page du site en construction de <b> $this->app_name </b> </p> </div>");
            $this->em->persist($post);
            $this->em->flush();
        }
    }

}
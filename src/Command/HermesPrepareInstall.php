<?php
namespace App\Command;

use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class HermesPrepareInstall extends Command
{
    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'hermes:prepare-directories';

    protected $container;
    protected $filesystem;

    public function __construct(Filesystem $filesystem, ParameterBagInterface $container)
    {
        $this->filesystem = $filesystem;
        $this->container = $container;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName(self::$defaultName)
            // the short description shown while running "php bin/console list"
            ->setDescription('Add, if not in public directory, directories storing uploaded files')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // ... put here the code to run in your command
        $created = $this->addInstallDir();

        // outputs a message without adding a "\n" at the end of the line

        foreach ($created as $dir){
            $output->writeln('directorie created: ' . $dir);
        }

        // this method must return an integer number with the "exit status code"
        // of the command. You can also use these constants to make code more readable

        // return this if there was no problem running the command
        // (it's equivalent to returning int(0))
        return Command::SUCCESS;

        // or return this if some error happened during the execution
        // (it's equivalent to returning int(1))
        // return Command::FAILURE;
    }

    private function addInstallDir(){
        try {
            $base = getcwd();
            $public = $base.'/public';
            //add public/build directory (webpack-encore)
            $build = $public.'/build';
            $this->filesystem->mkdir($build);
            //add database directory
            $pos = strlen( 'sqlite:///%kernel.project_dir%/' );
            $data = substr($this->container->get('hermes_database_dir'), $pos);
            $this->hermes_database_dir = explode('/',$data );
            // add uploaded file directories
            $creat1 = $this->addDir($this->hermes_database_dir, $base);
            $data_config = substr($this->container->get('hermes_database_config_dir'), $pos);
            $this->hermes_database_config_dir = explode('/',$data_config );
            $creat1_config = $this->addDir($this->hermes_database_config_dir, $base);
            $this->hermes_path_content_image = explode('/',$this->container->get('hermes_path_content_image') );
            $creat2 = $this->addDir($this->hermes_path_content_image,$public);
            $this->hermes_path_content_image_post = explode('/',$this->container->get('hermes_path_content_image_post'));
            $creat3 = $this->addDir($this->hermes_path_content_image_post,$public);
            $this->hermes_path_cache_image = explode('/',$this->container->get('hermes_path_cache_image'));
            $creat4 = $this->addDir($this->hermes_path_cache_image,$public);
        } catch (IOExceptionInterface $exception) {
            echo "An error occurred while creating your directories at ".$exception->getPath();
        }

        $created = array_merge($creat1, $creat1_config, $creat2, $creat3, $creat4);

        return $created;

    }

    private function addDir($path, $base ){
        $add_dir = '';
        $created =[];
        foreach ($path as $dir){
            if(!strpos($dir, '.' ) && '..' != $dir){
                $add_dir .= '/'.$dir;
                $this->filesystem->mkdir($base.$add_dir);
                $created[] = $base.$add_dir;
                if(str_contains($add_dir, 'uploads/entity')){
                    $this->filesystem->mkdir($base.$add_dir.'/Config');
                    $created[] = $base.$add_dir.'/Config';
                }
            }
        }
        return $created;

    }
}
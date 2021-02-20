<?php

namespace App\Command;

use App\BaseConsoleCommand;
use App\Command\MakeCrud\MakeCrudInfo;
use App\Helper\StringHelper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\Question;
use Twig\Environment;

class MakeCrudCommand extends BaseConsoleCommand
{

    /**
     * @var Environment
     */
    protected $twig;

    public function __construct(EntityManagerInterface $em, Environment $twig)
    {
        parent::__construct($em);

        $this->twig = $twig;
    }

    protected function configure()
    {
        $this
            ->setName('app:make:crud')
            ->addOption('entity', null, InputOption::VALUE_NONE, 'Generate entity')
            ->addOption('form', null, InputOption::VALUE_NONE, 'Generate symfony form')
            ->addOption('vue', null, InputOption::VALUE_NONE, 'Generate VueJS frontend files')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        parent::execute($input, $output);

        $crudInfo = new MakeCrudInfo();


        // GENERAL
        $crudInfo->setResourceSingularName(
            $this->askText('Resource singular name: ',
                $this->validateNotEmpty('Value required'))
        );

        $crudInfo->setResourcePluralName(
            $this->askText('Resource plural name: ',
                $this->validateNotEmpty('Value required'))
        );

        $crudInfo->setResourceTag(
            $this->askText('Resource tag',
                $this->validateNotEmpty('Value required'), $crudInfo->getResourcePluralName())
        );

        $crudInfo->setEntityFQNC(
            $this->askText('Entity FQCN',
                $this->validateNotEmpty('Value required'), 'App\Entity\\' . $crudInfo->getResourceSingularName())
        );

        $crudInfo->setEntityShortName(
            $this->askText('Entity short name',
                $this->validateNotEmpty('Value required'), $crudInfo->getResourceSingularName())
        );


        // GET ITEMS OPERATION
        $output->writeln('<info>GetItems section</info>');

        $crudInfo->setGetItemsOperationUrl(
            $this->askText('GetItems url',
                $this->validateNotEmpty('Value required'), '/' . StringHelper::slugify($crudInfo->getResourcePluralName()))
        );

        $crudInfo->setGetItemsOperationRouteName(
            $this->askText('GetItems route name',
                $this->validateNotEmpty('Value required'), 'get_' . StringHelper::tableize($crudInfo->getResourcePluralName()))
        );

        $crudInfo->setGetItemsOperationMethodName(
            $this->askText('GetItems controller method name',
                $this->validateNotEmpty('Value required'), 'get' . $crudInfo->getResourcePluralName())
        );

        $crudInfo->setGetItemsOperationResponseDescription(
            $this->askText('GetItems response description',
                $this->validateNotEmpty('Value required'), $this->generateDescriptionText($crudInfo->getResourcePluralName(), true) . ' list')
        );


        // GET ITEM OPERATION
        $output->writeln('<info>GetItem section</info>');

        $crudInfo->setGetItemOperationRouteName(
            $this->askText('GetItem route name',
                $this->validateNotEmpty('Value required'), 'get_' . StringHelper::tableize($crudInfo->getResourceSingularName()))
        );

        $crudInfo->setGetItemOperationMethodName(
            $this->askText('GetItem controller method name',
                $this->validateNotEmpty('Value required'), 'get' . $crudInfo->getResourceSingularName())
        );

        $crudInfo->setGetItemOperationResponseDescription(
            $this->askText('GetItem response description',
                $this->validateNotEmpty('Value required'), $this->generateDescriptionText($crudInfo->getResourceSingularName()) . ' resource')
        );


        // POST ITEM OPERATION
        $output->writeln('<info>PostItem section</info>');

        $crudInfo->setPostItemOperationRouteName(
            $this->askText('PostItem route name',
                $this->validateNotEmpty('Value required'), 'post_' . StringHelper::tableize($crudInfo->getResourceSingularName()))
        );

        $crudInfo->setPostItemOperationMethodName(
            $this->askText('PostItem controller method name',
                $this->validateNotEmpty('Value required'), 'post' . $crudInfo->getResourceSingularName())
        );

        $crudInfo->setPostItemOperationResponseDescription(
            $this->askText('PostItem response description',
                $this->validateNotEmpty('Value required'), 'New ' . $this->generateDescriptionText($crudInfo->getResourceSingularName()) . ' resource')
        );

        $crudInfo->setPostItemOperationFormType(
            $this->askText('PostItem form type',
                $this->validateNotEmpty('Value required'), $crudInfo->getResourceSingularName() . 'PostType')
        );


        // PATCH ITEM OPERATION
        $output->writeln('<info>PatchItem section</info>');

        $crudInfo->setPatchItemOperationRouteName(
            $this->askText('PatchItem route name',
                $this->validateNotEmpty('Value required'), 'patch_' . StringHelper::tableize($crudInfo->getResourceSingularName()))
        );

        $crudInfo->setPatchItemOperationMethodName(
            $this->askText('PatchItem controller method name',
                $this->validateNotEmpty('Value required'), 'patch' . $crudInfo->getResourceSingularName())
        );

        $crudInfo->setPatchItemOperationResponseDescription(
            $this->askText('PatchItem response description',
                $this->validateNotEmpty('Value required'), 'Patch ' . $this->generateDescriptionText($crudInfo->getResourceSingularName()) . ' resource')
        );

        $crudInfo->setPatchItemOperationFormType(
            $this->askText('PatchItem form type',
                $this->validateNotEmpty('Value required'), $crudInfo->getResourceSingularName() . 'PostType')
        );


        // DELETE ITEM OPERATION
        $output->writeln('<info>DeleteItem section</info>');

        $crudInfo->setDeleteItemOperationRouteName(
            $this->askText('DeleteItem route name',
                $this->validateNotEmpty('Value required'), 'delete_' . StringHelper::tableize($crudInfo->getResourceSingularName()))
        );

        $crudInfo->setDeleteItemOperationMethodName(
            $this->askText('DeleteItem controller method name',
                $this->validateNotEmpty('Value required'), 'delete' . $crudInfo->getResourceSingularName())
        );

        $crudInfo->setDeleteItemOperationResponseDescription(
            $this->askText('DeleteItem response description',
                $this->validateNotEmpty('Value required'), 'Delete ' . $this->generateDescriptionText($crudInfo->getResourceSingularName()) . ' resource')
        );

        $this->generate($crudInfo);

        return 0;
    }

    protected function generate(MakeCrudInfo $crudInfo)
    {
        $this->generateResourceController($crudInfo);

        if ($this->input->getOption('entity')) {
            $this->generateEntity($crudInfo);
        }

        if ($this->input->getOption('form')) {
            $this->generateForm($crudInfo);
        }

        if ($this->input->getOption('vue')) {
            $this->generateFrontend($crudInfo);
        }
    }

    protected function generateResourceController(MakeCrudInfo $crudInfo)
    {
        $content = $this->twig->render('MakeCrud/ResourceController.php.twig', ['info' => $crudInfo]);
        $filePath = __DIR__ . '/../Controller/Api/V1/Resource/' . $crudInfo->getResourceSingularName() . 'Resource.php';

        if (file_exists($filePath) && $this->confirm(sprintf('File %s already exists. Overwrite?', $filePath)) === false) {
            return;
        }

        file_put_contents($filePath, $content);
    }

    protected function generateEntity(MakeCrudInfo $crudInfo)
    {
        $filePath = __DIR__ . '/../Entity/' . $crudInfo->getResourceSingularName() . '.php';

        if (!file_exists($filePath) && $this->confirm(sprintf('Generate entity? '))) {
            $command = $this->getApplication()->find('make:entity');

            $arguments = [
                'command' => 'make:entity',
                'name'    => $crudInfo->getResourceSingularName()
            ];

            $input = new ArrayInput($arguments);

            $command->run($input, $this->output);

            require_once $filePath;
        }
    }

    protected function generateForm(MakeCrudInfo $crudInfo)
    {
        $filePath = __DIR__ . '/../Form/' . $crudInfo->getPostItemOperationFormType() . '.php';

        if (!file_exists($filePath) && $this->confirm(sprintf('Generate form? '))) {
            $command = $this->getApplication()->find('make:form');

            $arguments = [
                'command' => 'make:form',
                'name'    => $crudInfo->getPostItemOperationFormType(),
                'bound-class' => $crudInfo->getEntityShortName()
            ];

            $input = new ArrayInput($arguments);

            $command->run($input, $this->output);

            require_once $filePath;
        }
    }

    protected function generateFrontend(MakeCrudInfo $crudInfo)
    {
        // make directories

        $crudInfo->setFrontendPluginDirName(
            $this->askText('Frontend plugin directory name',
                $this->validateNotEmpty('Value required'), StringHelper::slugify($crudInfo->getResourceSingularName() . 's'))
        );

        $pluginsDir = __DIR__ . '/../../assets/js/plugins';
        $pluginDir  = $pluginsDir . '/' . $crudInfo->getFrontendPluginDirName();

        if (!file_exists($pluginDir)) {
            $this->output->writeln(sprintf('<info>Creating directory %s</info>', $pluginDir));
            mkdir($pluginDir);
        }

        if (!file_exists($pluginDir . '/view')) {
            $this->output->writeln(sprintf('<info>Creating directory %s</info>', $pluginDir . '/view'));
            mkdir($pluginDir . '/view');
        }

        $this->generateFrontendFiles($crudInfo, $pluginDir);
    }

    protected function generateFrontendFiles(MakeCrudInfo $crudInfo, string $pluginDir)
    {
        $this->generateFrontendIndexFile($crudInfo, $pluginDir);
        $this->generateFrontendRouterFile($crudInfo, $pluginDir);
        $this->generateFrontendListFile($crudInfo, $pluginDir);
        $this->generateFrontendEditFormFile($crudInfo, $pluginDir);
        $this->generateFrontendAddFormFile($crudInfo, $pluginDir);
        $this->generateFrontendBaseFormFile($crudInfo, $pluginDir);
    }

    protected function generateFrontendIndexFile(MakeCrudInfo $crudInfo, string $pluginDir)
    {
        if (file_exists($pluginDir . '/index.js')) {
            return;
        }

        $this->output->writeln('<info>Generating frontend plugin index file</info>');

        $content = <<<EOL
import routes from './routes';

export default {

    install(_, options) {
        options.routes[0].children = [...options.routes[0].children, ...routes];
    }

}
EOL;
        file_put_contents($pluginDir . '/index.js', $content);
    }

    protected function generateFrontendRouterFile(MakeCrudInfo $crudInfo, string $pluginDir)
    {
        if (file_exists($pluginDir . '/routes.js')) {
            return;
        }

        $this->output->writeln('<info>Generating frontend router file</info>');

        $crudInfo->setFrontendListPageTitle(
            $this->askText('List page title: ',
                $this->validateNotEmpty('Value required'), 'List page')
        );
        $crudInfo->setFrontendListPageRouteName(
            $this->askText('List page route name',
                $this->validateNotEmpty('Value required'), $crudInfo->getResourceSingularName() . 'sList')
        );
        $crudInfo->setFrontendListPageUrl(
            $this->askText('List page url',
                $this->validateNotEmpty('Value required'), $crudInfo->getGetItemsOperationUrl())
        );


        $crudInfo->setFrontendEditPageTitle(
            $this->askText('Edit page title: ',
                $this->validateNotEmpty('Value required'), 'Edit page')
        );
        $crudInfo->setFrontendEditPageRouteName(
            $this->askText('Edit page route name',
                $this->validateNotEmpty('Value required'), $crudInfo->getResourceSingularName() . 'Edit')
        );
        $crudInfo->setFrontendEditPageUrl(
            $this->askText('Edit page url',
                $this->validateNotEmpty('Value required'), $crudInfo->getFrontendListPageUrl() . '/:id(\\\d+)')
        );


        $crudInfo->setFrontendAddPageTitle(
            $this->askText('Add page title: ',
                $this->validateNotEmpty('Value required'), 'Add page')
        );
        $crudInfo->setFrontendAddPageRouteName(
            $this->askText('Add page route name',
                $this->validateNotEmpty('Value required'), $crudInfo->getResourceSingularName() . 'Add')
        );
        $crudInfo->setFrontendAddPageUrl(
            $this->askText('Add page url',
                $this->validateNotEmpty('Value required'), $crudInfo->getFrontendListPageUrl() . '/add')
        );


        $content = $this->twig->render('MakeCrud/js/routes.twig', ['info' => $crudInfo]);

        file_put_contents($pluginDir . '/routes.js', $content);
    }

    protected function generateFrontendListFile(MakeCrudInfo $crudInfo, string $pluginDir)
    {
        if (file_exists($pluginDir . '/view/List.vue')) {
            return;
        }

        $this->output->writeln('<info>Generating frontend List file</info>');

        $crudInfo->setFrontendListPageAddResourceText(
            $this->askText('List page Add resource text',
                $this->validateNotEmpty('Value required'), 'Add new ' . $this->generateDescriptionText($crudInfo->getResourceSingularName()))
        );

        $content = $this->twig->render('MakeCrud/js/view/List.twig', ['info' => $crudInfo]);

        file_put_contents($pluginDir . '/view/List.vue', $content);
    }

    protected function generateFrontendEditFormFile(MakeCrudInfo $crudInfo, string $pluginDir)
    {
        if (file_exists($pluginDir . '/view/Edit.vue')) {
            return;
        }

        $this->output->writeln('<info>Generating frontend Edit file</info>');

        $content = $this->twig->render('MakeCrud/js/view/Edit.twig', ['info' => $crudInfo]);

        file_put_contents($pluginDir . '/view/Edit.vue', $content);
    }

    protected function generateFrontendAddFormFile(MakeCrudInfo $crudInfo, string $pluginDir)
    {
        if (file_exists($pluginDir . '/view/Add.vue')) {
            return;
        }

        $this->output->writeln('<info>Generating frontend Add file</info>');

        $content = $this->twig->render('MakeCrud/js/view/Add.twig', ['info' => $crudInfo]);

        file_put_contents($pluginDir . '/view/Add.vue', $content);
    }

    protected function generateFrontendBaseFormFile(MakeCrudInfo $crudInfo, string $pluginDir)
    {
        if (file_exists($pluginDir . '/view/Form.vue')) {
            return;
        }

        $this->output->writeln('<info>Generating frontend Base Form file</info>');

        $content = $this->twig->render('MakeCrud/js/view/Form.twig', ['info' => $crudInfo]);

        file_put_contents($pluginDir . '/view/Form.vue', $content);
    }

    protected function askText(string $question, callable $validator, $default = null)
    {
        /** @var QuestionHelper $questionHelper */
        $questionHelper = $this->getHelper('question');

        if ($default) {
            $question = sprintf('%s, default (%s): ', $question, $default);
        }

        $question = new Question($question, $default);
        $question->setTrimmable(true);
        $question->setValidator($validator);

        return $questionHelper->ask($this->input, $this->output, $question);
    }

    protected function confirm(string $question)
    {
        /** @var QuestionHelper $questionHelper */
        $questionHelper = $this->getHelper('question');

        $question = new ConfirmationQuestion($question);

        return $questionHelper->ask($this->input, $this->output, $question);
    }

    protected function validateNotEmpty(string $message)
    {
        return function($answer) use ($message) {
            if (!$answer) {
                throw new \Exception($message);
            }

            return $answer;
        };
    }

    protected function generateDescriptionText(string $source, $plural = false)
    {
        $tmp = StringHelper::tableize($source);
        $tmp = explode('_', $tmp);

        if ($plural) {
            $tmp[count($tmp)-1] = $tmp[count($tmp)-1] . 's';
        }

        $tmp = implode(' ', $tmp);
        $tmp = ucfirst($tmp);

        return $tmp;
    }
}
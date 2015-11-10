<?php

namespace DrupalCodeGenerator\Commands\Drupal_8\Module;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use DrupalCodeGenerator\Commands\BaseGenerator;

/**
 * Implements d8:module command.
 */
class Module extends BaseGenerator {

  protected $name = 'd8:module:standard';
  protected $description = 'Generate standard Drupal 8 module';

  /**
   * {@inheritdoc}
   */
  protected function interact(InputInterface $input, OutputInterface $output) {

    $questions = [
      'name' => ['Module name', [$this, 'defaultName']],
      'machine_name' => ['Module machine name', [$this, 'defaultMachineName']],
      'description' => ['Module description', 'TODO: Write description for the module'],
      'package' => ['Package', 'custom'],
      'version' => ['Version', '8.x-1.0-dev'],
    ];

    $vars = $this->collectVars($input, $output, $questions);

    $prefix = $vars['machine_name'] . '/' . $vars['machine_name'];
    $this->files[$prefix . '.info.yml'] = $this->render('d8/module-info.yml.twig', $vars);
    $this->files[$prefix . '.module'] = $this->render('d8/module.twig', $vars);
    $this->files[$prefix . '.install'] = $this->render('d8/install.twig', $vars);
    $this->files[$prefix . '.libraries.yml'] = $this->render('d8/libraries.yml.twig', $vars);

    $controller_class = $this->human2class($vars['name'] . 'Controller');
    $this->files[$prefix . '.routing.yml'] = $this->render('d8/routing.yml.twig', $vars + ['class' => $controller_class]);
    $controller_path = $vars['machine_name'] . "/src/Controller/$controller_class.php";
    $this->files[$controller_path] = $this->render('d8/controller.twig', $vars + ['class' => $controller_class]);

    $form_path = $vars['machine_name'] . '/src/Form/SettingsForm.php';
    $this->files[$form_path] = $this->render('d8/form-config.twig', $vars + ['class' => 'SettingsForm']);

  }

}

<?php

namespace DavidBarratt\DrupalStructure;

use Composer\Script\Event;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

class Structure {

    protected $event;

    protected $fs;

    protected $root;

    protected $core;

    protected $finder;

    public function __construct(Event $event, Filesystem $fs, Finder $finder)
    {
        $this->event = $event;
        $this->fs = $fs;
        $this->finder = $finder;

        $composer = $event->getComposer();
        $extra = $composer->getPackage()->getExtra();

        if (!empty($extra['drupal-structure']['root'])) {
          $this->root = rtrim($extra['drupal-structure']['root'], '/') . '/';
        }
        else {
          $this->root = '';
        }

        $this->core = $this->root.'core/';

    }

    public function run() {

      if (!$this->fs->exists($this->core)) {
          return;
      }

      $dirs = array(
        $this->root.'modules/',
        $this->root.'themes/',
        $this->root.'sites/',
      );

      $this->fs->mkdir($dirs, 0750);

      $files = array(
        'README.txt',
        'example.sites.php'
      );

      $this->copyFiles('sites/', 'sites/', $files);

      if (!is_link($this->core.'sites/default')) {

        $files = array(
          'default.settings.php',
        );

        $this->copyFiles('sites/default/', 'sites/default/', $files);

      }

      $this->finder->directories()->in($this->root.'sites/');

      $sites = array();
      foreach ($this->finder as $folder) {
        $site = $folder->getRelativePathname();
        $folder_name = $site.'/';

        if (!is_link($this->core.'sites/'.$site)) {
          $this->fs->remove($this->core.'sites/'.$folder_name);
          $this->fs->symlink('../../sites/'.$folder_name, $this->core.'sites/'.$site);
        }

      }

      $contrib = array(
        'modules',
        'themes',
      );

      $files = array(
        'README.txt',
      );

      foreach ($contrib as $type) {

        $folder = $type.'/';

        if (!is_link($this->core.'sites/all/'.$type)) {

          $this->copyFiles('sites/all/'.$folder, $folder, $files);

          $this->fs->remove($this->core.'sites/all/'.$folder);

          $this->fs->symlink('../../../'.$folder, $this->core.'sites/all/'.$type);

        }


      }

      if (!$this->fs->exists($this->root.'index.php')) {
        $this->fs->copy($this->core.'index.php', $this->root.'index.php');
      }

      if (!is_link($this->core.'sites/sites.php')) {

        $this->fs->touch($this->root.'sites/sites.php');
        $this->fs->symlink('../.../sites/sites.php', $this->core.'sites/sites.php');

      }


    }

    /**
     * Copy Files
     *
     * @param string $source Initial Folder to start from.
     * @param string $destination Desitnation Folder to end.
     * @param array $files Files to copy.
     *
     * @return null
     */
    public function copyFiles($source, $destination, $files) {

      foreach ($files as $file) {

        if (!$this->fs->exists($this->root.$destination.$file)) {

          if ($this->fs->exists($this->core.$source.$file)) {
            $this->fs->copy($this->core.$source.$file, $this->root.$destination.$file);
          }

        }

      }

    }

    public function setEvent(Event $event) {
      $this->event = $event;
    }

    public function getEvent() {
      $this->event;
    }

    public function setFilesystem(Filesystem $fs) {
      $this->fs = $fs;
    }

    public function getFilesystem() {
      $this->fs;
    }

    public function setFinder(Finder $finder) {
      $this->finder = $finder;
    }

    public function getFinder() {
      $this->finder;
    }

    public function setRoot($root) {
      $this->root = $root;
    }

    public function getRoot() {
      $this->root;
    }

    public function setCore($core) {
      $this->core = $core;
    }

    public function getCore() {
      $this->core;
    }


}

<?php
/**
Class <code>AbstractFactory</code> implements an example of the Creational Desing Patterns
- AbstractFactory
- Singleton

Please note that the comments are mainly informative and the structure for ease-of-handling.
The usage of the PHP-sources are to demonstrate the AbstractFactory and Singleton pattern and
and not good PHP practice/coding.
Used are:
= Type ---- Name ------------------ Implements ----------------------------------------------------------
* Class     AbstractFactoryDemo     Demo usage of the AbstractFactory Pattern.
* Class     GUIBuilder              The builder that is called by the User (in AbstractFactoryDemo)
* Interface Window                  Abstract implementation for setTitle and repaint.
* Class     MSWindow                Implementation for Interface Window for MS-Windows.
* Class     MacOSXWindow            Implementation for Interface Window for Mac-OSX.
* Interface AbstractWidgetFactory   Abstract implementation for createWindow
* Class     MsWindowsWidgetFactory  Implementation for Interface AbstractWidgetFactory for MS-Windows.
* Class     MacOSXWidgetFactory     Implementation for Interface MacOSXWidgetFactory for Mac-OSX.
*
*
* All classes are placed in this single file for simplicity and ease of usage.
*
* See the documentation on https://www.harmfrielink.nl/wiki/index.php/Abstract_Factory.
*/

/**
 * The class <code>AbstractFactoryDemo</code> is build on the client app and uses the AbstractFactory pattern.
 */
class AbstractFactoryDemo {
   /**
    * Main entry, constructor.
    */
   public function __construct() {
      $builder       = new GUIBuilder();
      $widgetFactory = null;

      printf("%-20.20s: %s\n", "Current platform", PHP_OS);

      $currentPlatform = Util::getOS();
      $currentPlatform = "qq";
      switch( $currentPlatform ) {
         case Util::APPLE_OSX:
         case Util::LINUX:
                                 $widgetFactory = new MacOSXWidgetFactory();
                                 break;
         case Util::MS_WINDOWS:
                                 $widgetFactory = new MsWindowsWidgetFactory();
                                 break;
         default:
                                 print("About to throw an exception.\n");
                                 throw new Exception("Unknown platform " + $currentPlatform);
                                 break;
      }  // switch

      $builder->buildWindow( $widgetFactory);
   }
}  // class AbastractFactoryDemo

/**
 * The GUIBuilder Client which is the only entry point.
 * The first main entry point for the client-application uses:
 * - Interface Window.
 */
class GUIBuilder {
   /**
    * Builds the window.
    * @param AbstractWidgetFactory $widgetFactory Implementation for the AbstractWidgetFactory (MacOSXWidgetFactory or MsWindowsWidgetFactory).
    */
   public function buildWindow(AbstractWidgetFactory $widgetFactory) {
      // Gets the correct wondow Implementation.
      $window = $widgetFactory->createWindow();

      // Sets the window-title.
      $window->setTitle("New Window");
   }  // buildWindow
}  // GUIBuilder

/**
 * Abstract product implementation Window.
 */
interface window {
   /** Sets the title of the window. */
   public function setTitle($text);

   /** Repaint implementation. */
   public function repaint();
}  // interface Window

/**
 * Concrete product for Microsoft-Windows implementation of Interface Window.
 */
class MSWindow implements Window {

   /**
    * Sets the Title
    * @param  $title The name of the title.
    */
   public function setTitle($title) {
      // MS Windows specific behaviour
      printf("%-20.20s: %s\n", "MSWindow", $title);
   }  // setTitle

   public function repaint() {
      // MS Windows specific behaviour
   }
}  // MSWindow

/**
 * Concrete product for Mac-OSX implementation of Interface Window.
 */
class MacOSXWindow implements Window {

   /**
    * Sets the Title
    * @param  $title The name of the title.
    */
   public function setTitle($title) {
      // MaxOSX specific behaviour
      printf("%-20.20s: %s\n", "MacOSXSWindow", $title);
   }  // setTitle

   public function repaint() {
      // MacOSX specific behaviour
   }
}  // MaOSXWindow


/**
 * Abstractfactory for the Window.
 */
interface AbstractWidgetFactory {
   /** Creates the window */
   public function createWindow();
}  // AbstractWidgetFactory

/** Concrete Factory for MS-Windows. */
class MsWindowsWidgetFactory implements AbstractWidgetFactory {

   public function createWindow(){
      $window = new MSWindow();
      return $window;
   }  // createWindow
}  // MsWindowsWidgetFactory

/** Concrete Factory for MS-Windows. */
class MacOSXWidgetFactory implements AbstractWidgetFactory {

   public function createWindow(){
      $window = new MacOSXWindow();
      return $window;
   }  // createWindow
}  // MacOSXWidgetFactory


/**
 * Singleton-Static-Utility class implements generic requirements.
 * The class implements the needed functions as-basic-as-can-be.
 * The methods are NOT meant to be used as example/template for your own Utility-Class,
 * because the most basic test/validation on input are not doneat all.
 * Please note the way an instance of the class is returned/made.
 */
class Util {

   const APPLE_OSX   = "apple-OSX";
   const LINUX       = "linux";
   const MS_WINDOWS  = "MS-Windows";
   const OS_UNKNOWN  = "Unknown";

   /** Instance of the class. */
   private static $instance = null;

   /**
    * Private constructor meant to be never used at all.
    * Any attempt to call this method is doomed to fail.
    * @return $this
    */
   private function __construct() {
      // Deliberately nothing here.
   }  // __construct

   /**
    * Gets the only instance of the class Util.
    * @return type instance of Util.
    */
   public static function getInstance() {
      if (is_null(self::$instance) || ! (self::$instance instanceof Util)) {
        self::$instance = new Util;
      }
      return self::$instance;
   }  // getInstance

   /**
    * Gets the OS of the current running instance of PHP.
    * Only for CLI (Command Line Options) applications not WebApplications.
    * @return One of the defined instances of this Utility Class.
    */
   public static function getOS() {
      if ( self::detectOSX() ) {
         return self::APPLE_OSX;
      }

      if ( self::detectLinux() ) {
         return self::LINUX;
      }

      if ( self::detectWindows() ) {
         return self::MS_WINDOWS;
      }

      return self::OS_UNKNOWN;
   }  // getOS

   /**
    * Detects the Apple Mac OSX OS.
    * @return boolean true|false
    */
   private static function detectOSX() {
      if ( stristr( PHP_OS, "darwin") !== false ) {
         return true;
      }

      return false;
   }  // detectOSX

   /**
    * Detects the Cygwin/Unix/Linux OS.
    * @return boolean true|false
    */
   private static function detectLinux() {
      if ( stristr( PHP_OS, "cygwin") !== false ) {
         return true;
      }
      if ( stristr( PHP_OS, "linux") !== false ) {
         return true;
      }
      if ( stristr( PHP_OS, "unix") !== false ) {
         return true;
      }

      return false;
   }  // detecLinux

   /**
    * Detects the MS-Windows Family.
    * @return boolean true|false
    */
   private static function detectwindows() {
      if ( stristr( PHP_OS, "windows") !== false ) {
         return true;
      }
      if ( stristr( PHP_OS, "win32") !== false ) {
         return true;
      }
      if ( stristr( PHP_OS, "winnt") !== false ) {
         return true;
      }

      return false;
   }  // detectWindows
}  // class Util


/*
-- Starts the demo by calling the constructor of the OberserDemo-class.
*/
$abstractFactoryDemo = new AbstractFactoryDemo();


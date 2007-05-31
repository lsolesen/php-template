<?php
/**
 * package.xml generation script
 *
 * @package Template
 * @author  Lars Olesen <lars@legestue.net>
 * @since   0.1.0
 * @version @package-version@
 */

$version = '0.2.1';
$stability = 'alpha';
$notes = '
* initial release as PEAR
* added assign() and display() to implement same interface as Smarty and Savant3
* improved docblock comments';

require_once 'PEAR/PackageFileManager2.php';
PEAR::setErrorHandling(PEAR_ERROR_DIE);
$pfm = new PEAR_PackageFileManager2();
$pfm->setOptions(
    array(
        'baseinstalldir'    => 'Template',
        'filelistgenerator' => 'file',
        'packagedirectory'  => dirname(__FILE__),
        'packagefile'       => 'package.xml',
        'ignore'            => array(
            'generate_package_xml.php',
            '*.tgz'
            ),
        'exceptions'        => array(
        ),
        'simpleoutput'      => true,
    )
);

$pfm->setPackage('Template');
$pfm->setSummary('Template engine which uses php as markup language');
$pfm->setDescription('A really simple template engine.');
$pfm->setChannel('public.intraface.dk');
$pfm->setLicense('MIT Open Source License', 'http://opensource.org/osi3.0/licenses/mit-license.php');
$pfm->addMaintainer('lead', 'lsolesen', 'Lars Olesen', 'lars@legestue.net');

$pfm->setPackageType('php');

$pfm->setAPIVersion($version);
$pfm->setReleaseVersion($version);
$pfm->setAPIStability($stability);
$pfm->setReleaseStability($stability);
$pfm->setNotes($notes);
$pfm->addRelease();

$pfm->addGlobalReplacement('package-info', '@package-version@', 'version');

$pfm->clearDeps();
$pfm->setPhpDep('4.3.0');
$pfm->setPearinstallerDep('1.5.0');

$pfm->generateContents();

if (isset($_GET['make']) || (isset($_SERVER['argv']) && @$_SERVER['argv'][1] == 'make')) {
    if ($pfm->writePackageFile()) {
        exit('package file written');
    }
} else {
    $pfm->debugPackageFile();
}
?>
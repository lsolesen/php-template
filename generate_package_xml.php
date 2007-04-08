<?php
/**
 * package.xml generation script
 *
 * @package Template
 * @author  Lars Olesen <lars@legestue.net>
 * @since   0.1.0
 * @version @package-version@
 */

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
			'package.xml',
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
$pfm->setUri('http://localhost/');
$pfm->setLicense('LGPL License', 'http://www.gnu.org/licenses/lgpl.html');
$pfm->addMaintainer('lead', 'lsolesen', 'Lars Olesen', 'lars@legestue.net');

$pfm->setPackageType('php');

$pfm->setAPIVersion('0.1.0');
$pfm->setReleaseVersion('0.2.0');
$pfm->setAPIStability('alpha');
$pfm->setReleaseStability('beta');
$pfm->setNotes('Initial release as a PEAR package');
$pfm->addRelease();

$pfm->addGlobalReplacement('package-info', '@package-version@', 'version');

$pfm->clearDeps();
$pfm->setPhpDep('4.3.0');
$pfm->setPearinstallerDep('1.5.0');

$pfm->generateContents();

if (isset($_GET['make']) || (isset($_SERVER['argv']) && @$_SERVER['argv'][1] == 'make')) {
	echo 'write package file';
    $pfm->writePackageFile();
} else {
	echo 'debug package file';
    $pfm->debugPackageFile();
}
?>
<?php
/**
 * Author: Fedor Avetisov <cousenavi@gmail.com>
 * Date: 5/22/13
 */

namespace Yasoon\Site\Controller;

use Symfony\Component\HttpFoundation\Response;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use JMS\DiExtraBundle\Annotation as DI;
use Yasoon\Site\Service\ContentService;
use Yasoon\Site\Service\AuthorService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Export\ExcelBundle\Services\Export;
use PHPExcel;

/**
 * Class AdminController
 *
 * @Route("/export")
 * @package Yasoon\Site\Controller
 */
class AdminController extends Controller {

    /**
     * @var AuthorService
     *
     * @DI\Inject("yasoon.service.author")
     */
    private $authorservice;
    
    
    /**
     * @var \Symfony\Component\Security\Core\SecurityContextInterface
     *
     * @DI\Inject("security.context")
     */
    public  $securityContext;
    
    
    /**
     * @Route("/excel_users")
     * @Method({"GET"})
     *
     * @return array
     */
    public function excel_users()
    {
        $users = $this->authorservice->get_all();
        
        $options = array(
            'coordinates' => array(
                'x' => 0,
                'y' => 1,
            ),
            'labels' => array(
                'bold'       => true,
                'size'       => 18,
                'color'      => '#000000',
                'fill'       => '#FFFFFF',
                'height'     => 30,
                'wrap'       => false,
                'horizontal' => 'center',
            ),
            'zebra' => array(
                'color' => '#FF0000',
            ),
            'return' => true,
        );
        
        
        $labels = array(
            'id' => 'ID',
            'name'   => 'Name',
            'email'  => 'E-mail',
            'date' => 'Date',
            'reg_from' => 'Register From',
        );
        
        $data = [];
        foreach($users as $user)
        {
            $data[] = ['id' => $user['id'],
                       'name' => $user['name'],
                       'email' => $user['email'],
                       'date' => $user['date_reg'],
                       'reg_from' => $user['reg_from']];
        }
        

        $phpExcelObject = $this->get('export.excel');
        $phpExcelObject->createSheet();
        $phpExcelObject->setNameOfSheet('Users '.date('Y-m-d'));
        $phpExcelObject->writeTable($data, $labels, $options);
        $phpExcelObject->writeExport('users');
        
        return ['file_xls' => $_SERVER['HTTP_HOST'].'/web/users.xls'];
    }
    
    /**
     * @Route("/csv_users")
     * @Method({"GET"})
     *
     * @return array
     */
    public function csv_users()
    {
        
        $users = $this->authorservice->get_all();
        
        $result = '';
        foreach($users as $user)
        {
            $data = [$user['id'], $user['name'], $user['email'], $user['date_reg'], 'reg_from' => $user['reg_from']];
            $result .= implode(',', $data)."\n";
        }
        return ['result' => $result];
    }
    
    /**
     * @Route("/excel_email_users")
     * @Method({"GET"})
     *
     * @return array
     */
    public function excel_email_users()
    {
        $users = $this->authorservice->get_all_subscribed();
        
        $options = array(
            'coordinates' => array(
                'x' => 0,
                'y' => 1,
            ),
            'labels' => array(
                'bold'       => true,
                'size'       => 18,
                'color'      => '#000000',
                'fill'       => '#FFFFFF',
                'height'     => 30,
                'wrap'       => false,
                'horizontal' => 'center',
            ),
            'zebra' => array(
                'color' => '#FF0000',
            ),
            'return' => true,
        );
        
        
        $labels = array(
            'id' => 'ID',
            'name'   => 'Name',
            'email'  => 'E-mail',
            'date' => 'Date',
            'reg_from' => 'Register From'
        );
        
        $data = [];
        foreach($users as $user)
        {
            $data[] = ['id' => $user['id'],
                       'name' => $user['name'],
                       'email' => $user['email'],
                       'date' => $user['date_reg'],
                       'reg_from' => $user['reg_from']];
        }
        

        $phpExcelObject = $this->get('export.excel');
        $phpExcelObject->createSheet();
        $phpExcelObject->setNameOfSheet('Users '.date('Y-m-d'));
        $phpExcelObject->writeTable($data, $labels, $options);
        $phpExcelObject->writeExport('users_subscribed');
        
        return ['file_xls' => $_SERVER['HTTP_HOST'].'/web/users_subscribed.xls'];
    }
    
    
    
    
    /**
     * @Route("/csv_users_email")
     * @Method({"GET"})
     *
     * @return array
     */
    public function csv_users_email()
    {
        
        $users = $this->authorservice->get_all_subscribed();
        
        $result = '';
        foreach($users as $user)
        {
            $data = [$user['id'], $user['name'], $user['email'], $user['date_reg'], 'reg_from' => $user['reg_from']];
            $result .= implode(',', $data)."\n";
        }
        return ['result' => $result];
    }
}

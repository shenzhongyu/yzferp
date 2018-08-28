<?php
/**
 * Created by PhpStorm.
 * Power By Mikkle
 * Email：776329498@qq.com
 * Date: 2017/8/10
 * Time: 15:09
 */

namespace app\base\controller;


use think\Loader;

class PDF
{
    protected $tcpdf;
    public function __construct($options=[]) {
        if(empty($this->tcpdf)){
            Loader::import("com/TCPDF/tcpdf_import");
            $this->tcpdf=new \TCPDF();
            $this->tcpdf->SetCreator("深圳云紫峰网络科技股份有限公司");
            $this->tcpdf->SetAuthor('power by Mikkle');
            $this->tcpdf->SetTitle('深圳云紫峰网络科技股份有限公司');
            $this->tcpdf->SetSubject('深圳云紫峰网络科技股份有限公司');
            $this->tcpdf->SetKeywords('云紫峰');

// 设置页眉和页脚信息
            $this->tcpdf->SetHeaderData('../../../../../public_html/static/erp/images/login_logo.png', 40, 'www.yzfErp.com', '深圳云紫峰网络科技股份有限公司',
                array(0,64,128), array(0,64,128));
            $this->tcpdf->setFooterData(array(0,64,0), array(0,64,128));

// 设置页眉和页脚字体
            $this->tcpdf->setHeaderFont(Array('stsongstdlight', '', '10'));
            $this->tcpdf->setHeaderMargin(10);
            $this->tcpdf->setFooterFont(Array('helvetica', '', '8'));

// 设置默认等宽字体
            $this->tcpdf->SetDefaultMonospacedFont('courier');

// 设置间距
            $this->tcpdf->SetMargins(15, 27, 15);
            $this->tcpdf->SetHeaderMargin(5);
            $this->tcpdf->SetFooterMargin(10);

            // 设置分页
            $this->tcpdf->SetAutoPageBreak(TRUE, 25);
            $this->tcpdf->setImageScale(1.25);

        }
    }

    public function pdfCreate($name="doc",$html){
        set_time_limit(30);

        $this->tcpdf->AddPage();
        $this->tcpdf->writeHTML($html, true, false, false, false, '');
        $this->tcpdf->Output("{$name}.pdf","D");
    }

    static public function downLoadPDF($name="doc",$html){

        return (new PDF())->pdfCreate($name,$html);

    }

    /**
     * 魔术方法 有不存在的操作的时候执行
     * @access public
     * @param string $method 方法名
     * @param array $args 参数
     * @return mixed
     */
    public function __call($method, $args)
    {
        call_user_func_array([$this->tcpdf, $method], $args);
    }


}
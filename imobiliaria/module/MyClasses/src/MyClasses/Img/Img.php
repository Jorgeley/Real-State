<?php

namespace MyClasses\Img;

class Img {

    private $qtdMax;
    private $post;
    private $src;
    private $relPath;
    private $fullPath;
    private $larg;
    private $alt;

    /**
     * @tutorial faz o recorte de imagens
     * @param integer $qtdMax qtd de imagens a serem geradas, OBS: cada img terá o nome em sequência Ex: 1.jpg, 2.jpg
     * @param mixed $src array com as imagens de origem a serem recortadas
     * @param string $relPath caminho relativo das imagens, usadas nos links Ex: /arquivos/logomarcas/2.jpg
     * @param string $fullPath caminho absoluto das imagens, Ex: /www/projeto/arquivos/logomarcas/2.jpg OU c:\www\projeto\arquivos\logomarcas\2.jpg
     * @param integer $larg largura do recorte da imagem
     * @param integer $alt altura do recorte da imagem
     * @param integer $id identificador da imagem. OBS: qdo é uma img só, o nome fica $id.jpg, qdo são várias cria um diretório $id contendo imgs em sequencia Ex: $id/1.jpg
     * @return void
     */
    function __construct($qtdMax, $post, $src, $relPath, $fullPath, $larg, $alt, $id) {
        $this->qtdMax = $qtdMax;
        $this->post = $post;
        $this->src = $src;
        $this->relPath = $relPath;
        $this->fullPath = $fullPath;
        $this->larg = $larg;
        $this->alt = $alt;
        $this->id = $id;
    }

    public function recorta($larg=0, $alt=0, $id="") {
        $this->larg = ($larg!=0) ? $larg : $this->larg;
        $this->alt = ($alt!=0) ? $alt : $this->alt;
        $this->id = ($id!="") ? $id : $this->id;
        for ($i = 0; $i < $this->qtdMax; $i++) {
            //print_r($this->post);
            $x = $this->post['x' . $i];
            $y = $this->post['y' . $i];
            $w = $this->post['w' . $i];
            $h = $this->post['h' . $i];
            //echo "coordenadas: x=$x, y=$y, w=$w, h=$h";
            $src = $this->src[$i];
            $src = str_replace($this->relPath, $this->fullPath, $src);
            $ext = strtolower(substr($src, -3, 3));
            switch ($ext) {
                case 'jpg' : $img = imagecreatefromjpeg($src);
                    break;
                case 'png' : $img = imagecreatefrompng($src);
                    break;
                case 'gif' : $img = imagecreatefromgif($src);
                    break;
                default : $img = imagecreatefromjpeg($src);
            }
            //imagejpeg($img,$src."j");
            $imgDest = imagecreatetruecolor($this->larg, $this->alt);
            list($wsrc, $hsrc) = getimagesize($src);
            //proporções corretas para qdo a imagem é menor que o mínimo
            if ($wsrc <= $this->larg) {
                $w = $wsrc;
                $h = ($this->alt / $this->larg) * $w;
            } else if ($hsrc <= $this->alt) {
                $h = $hsrc;
                $w = ($this->larg / $this->alt) * $h;
            }
            //echo $x."-".$y."-".$this->larg."-".$this->alt."-".$w."-".$h;
            imagecopyresampled($imgDest, $img, 0, 0, $x, $y, $this->larg, $this->alt, $w, $h);
            switch ($ext) {
                case 'jpg' : imagejpeg($imgDest, $this->fullPath.$this->id.'.'.$ext, 90);
                    break;
                case 'png' : imagepng($imgDest, $src, 90);
                    break;
                case 'gif' : imagegif($imgDest, $src, 90);
                    break;
                default : imagejpeg($imgDest, $src, 90);
            }
        }
    }

    public function mostra() {
        echo "qtdMax: " . $this->qtdMax . "<br>";
        echo "Post: " . print_r($this->post) . "<br>";
        echo "src: " . print_r($this->src) . "<br>";
        echo "relPath: " . $this->relPath . "<br>";
        echo "fullPath: " . $this->fullPath . "<br>";
        echo "larg: " . $this->larg . "<br>";
        echo "alt: " . $this->alt . "<br>";
        echo "id: " . $this->id . "<br>";
    }

}

?>
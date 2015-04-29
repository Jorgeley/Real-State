<?php
namespace MyClasses\View\Helpers;
use Zend\View\Helper\AbstractHelper;

class CarrosselProdutos extends AbstractHelper{
	protected $dados;
	
	public function __invoke($produtos,$limite){
		$this->dados['carrossel'] = "";
		$this->dados['carrossel'].="<ul id='carosel'>";
		$c=0;
		if (is_array($produtos)): 
			while ($produto = array_shift($produtos)):
	 			$this->dados['carrossel'].="<li>";
				$del = "<a href='#' OnClick=\"ajax('/testdrive/setup/excluirproduto/".$produto['nome']."','GET',null);\" title='excluir' id='excluir' style='margin-bottom:-20px;float:left;position:relative;text-shadow:0 0 2px #fff;'>X</a>";
	 			$this->dados['carrossel'].=$del."<div class='ImagemProduto'>";
	 			$this->dados['carrossel'].="<a href='#'><img src='".$produto['img']."' width='100' height='100' title='".$produto['nome']."' /></a>";
	 			$this->dados['carrossel'].="</div>";
	 			$this->dados['carrossel'].="<p class='DescricaoProduto Descricao'>";
	 			$this->dados['carrossel'].="<a href='#'>".$produto['nome']."</a>";
	 			$this->dados['carrossel'].="</p>";
	 			if ($produto['tipo']=='p'):
	 				$this->dados['carrossel'].="<p class='DescontoProduto'>";
	 				$this->dados['carrossel'].="<a href='#'>De: R$<span>".number_format($produto['de'],2,',','.')."</span></a>";
	 				$this->dados['carrossel'].="</p>";
	 				$this->dados['carrossel'].="<p class='valorProduto Preco'>";
	 				$this->dados['carrossel'].="<a href='#'>Por: <b>R$</b> ".number_format($produto['precop'],2,',','.')." <span>Em até ".$produto['parcelasp']."x</span></a>";
		 			$this->dados['carrossel'].="</p>";
	 			endif;
	 			if ($produto['tipo']=='v'):
	 				$this->dados['carrossel'].="<p class='valorProduto Preco'>";
					$this->dados['carrossel'].="<a href='#'>Por: <b>R$</b> ".number_format($produto['precov'],2,',','.')."<br><span>À vista</span></a>";
					$this->dados['carrossel'].="</p>";
				endif;
				if ($produto['tipo']=='x'):
					$this->dados['carrossel'].="<p class='valorProduto Preco'>";
					$this->dados['carrossel'].="<a href='#'>Por: <b>R$</b> ".number_format($produto['precox'],2,',','.')." <br><span>Em até ".$produto['parcelas']."x</span></a>";
					$this->dados['carrossel'].="</p>";
				endif; 
				$c++;
				if ($c == $limite): break; endif;
	 			$this->dados['carrossel'].="</li>";
			endwhile;
		endif;
		if ($c<$limite):
			for ($i=1;$i <= ($limite-$c);$i++):
				$this->dados['carrossel'].="<li>";
				$this->dados['carrossel'].="<div class='ImagemProduto'>";
				$this->dados['carrossel'].="<a href='#'>";
				$this->dados['carrossel'].="<img src='http://static.hippershopping.com.br/img/1.png' width='100' height='100' title='nome e descrição do produto' />";
				$this->dados['carrossel'].="</a>";
				$this->dados['carrossel'].="</div>";
				$this->dados['carrossel'].="<p class='DescricaoProduto Descricao'>";
				$this->dados['carrossel'].="<a href='#'>nome e descrição do produto</a>";
				$this->dados['carrossel'].="</p>";
				$this->dados['carrossel'].="<p class='DescontoProduto'>";
				$this->dados['carrossel'].="<a href='#'>De: R$<span>999.999,99</span></a>";
				$this->dados['carrossel'].="</p>";
				$this->dados['carrossel'].="<p class='valorProduto Preco'>";
				$this->dados['carrossel'].="<a href='#'>Por: <b>R$</b> 999.999,99<span>Em até 6x</span></a>";
				$this->dados['carrossel'].="</p>";
				$this->dados['carrossel'].="</li>";
			endfor;
		endif;					
		$this->dados['carrossel'].="</ul>";
		$this->dados['produtos'] = $produtos;
		return $this->dados;
	}
}
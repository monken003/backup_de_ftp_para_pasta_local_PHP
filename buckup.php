<?php
//essas 3 linhas tem a função de conectar com o servidor
$dados = array("host" => "IP DO SERVIDOR", "usuario" => "USUARIO DO SV", "senha" => "SENHA DO USUSARIO DO SV");
$fconn = ftp_connect($dados["host"]) or die("Falhou".$dados["host"]);
ftp_login($fconn, $dados["usuario"], $dados["senha"]);


//essas 6 linhas tem a fução de localizar a pasta pra onde vai ser levado os arquivos, e verificar também, se o arquivo já foi enviado, para numa proxima vez não ter que enviar novamente.
unset($arr_arq_ja_baixados_scripts);
 $raiz_scripts = dir("../../../localização da pasta onde vai ser levado os arquivos");
 while(($pasta_scripts = $raiz_scripts->read()) !== false){
     if($pasta_scripts == "." || $pasta_scripts == "..") continue;
     $arr_arq_ja_baixados_scripts["$pasta_scripts"] = 1;
 }

/*essas 9 linhas tem a função de:
1 localizar a pasta onde vai copiar os arquivos dentro do servidor
2 fazer um array com cada nome de cada arquivo
3 dar um substr para ver qual a instenção exata, para saber qual deve puxar, ex:
	se os arquivos forem de extenção .exe, você deve ter esta linha dessa forma, 
	if(substr($arquivo_scripts,-4) == ".exe"){
	aí, todo arquivo que for copiado vai ter a extenção .exe.
4 ele da um subsrt fazendo com que pegue apenas o nome do arquivo ex:
	oque esta "www/fmdkfmdkf/nomedoarquivo.tar.gz" fica só "nomedoarquivo.tar.gz".
5 verifica se o arquivo foi já baixado atraves das 6 linhas acima de codico que usamos.
6 após tudo isso ele baixa todos os arquivos que estavam naquela pasta que ainda não foram baixados.
*/
 $arr_arquivo_scripts = ftp_nlist($fconn, "/www/pasta que vai ser copiado os arquivos no sv");
foreach($arr_arquivo_scripts as $arquivo_scripts){
    if(substr($arquivo_scripts,-7 /*no lugar do -7 vem a quantidade de caracteres que devemos escolher de tras pra frente*/) == ".tar.gz"/*no lugar do .tar.gz é o lugar onde vamos escolher qual a extenção que vamos usar de acordo com a quantidade de caraqteres final*/){
        $arquivo_limpo_scripts = substr($arquivo_scripts,strrpos($arquivo_scripts,"/")+1);
        if($arr_arq_ja_baixados_scripts[$arquivo_limpo_scripts] != 1){
            ftp_get($fconn, "../../../localização da pasta onde vai ser levado os arquivos".$arquivo_limpo_scripts, $arquivo_scripts, FTP_BINARY);
        }
    }
}

/* essas duas linhas tem a função de:
1 fechar a conexão ftp, para que não fique sempre abrindo e algum dia trave o ftp
2 fechar a pagina logo apos quando tudo for concluido
*/
ftp_close($fconn);

echo "<script>self.close()</script>";
?>

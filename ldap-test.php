<?php

echo "<h3>Teste de consulta LDAP</h3>";
echo "Conecttando...";

$hostname = "ldap://caxias.ifrs.edu.br";

$ds = ldap_connect($hostname);
ldap_set_option($ds, LDAP_OPT_REFERRALS, 0) or die('Não é possível definir referências de opt LDAP');
ldap_set_option($ds, LDAP_OPT_PROTOCOL_VERSION, 3) or die('Não é possível definir a versão do protocolo LDAP');
ldap_set_option($ds, LDAP_OPT_DEBUG_LEVEL, 7);

echo "O resultado da conexão é " . $ds . "<br />";

if ($ds) {
    echo "Populando ...";

    //$username = "gabriel.fernandes@caxias.ifrs.edu.br";
    //$psw = "xxx";
    $username = "troca.senhaad@caxias.ifrs.edu.br";
    $psw = "teste123";

    // se for uma ligação "anônima", geralmente, permite acesso somente leitura
    $r = ldap_bind($ds, $username, $psw);

    echo "O resultado da ligação foi " . $r . "<br />";

    echo "Buscando por (cn=*) ...";

    //$dn = "DC=caxias,DC=ifrs,DC=edu,dc=br";
    //$dn = "OU=Alunos,OU=Departamentos,DC=caxias,DC=ifrs,DC=edu,DC=br";
    $dn = "OU=TI,OU=Departamentos,DC=caxias,DC=ifrs,DC=edu,DC=br";

    $filter = "(cn=*)";
    try {

        $sr = ldap_search($ds, $dn, $filter);

    } catch (Exception $e) {
        echo 'Exceção capturada: ', $e->getMessage(), "\n";
    }

    echo "O resultado da busca é  " . $sr . "<br /><br />";
    echo "Número de registros retornados " . ldap_count_entries($ds, $sr) . "<br />";

    echo "Obtendo registros ...<p>";
    $info = ldap_get_entries($ds, $sr);
    echo "Os dados dos  " . $info["count"] . " registros retornados são:<p>";

    for ($i = 0; $i < $info["count"]; $i++) {
        echo "dn é: " . $info[$i]["dn"] . "<br />";
        echo "primeiro cn é: " . $info[$i]["cn"][0] . "<br />";

        if (array_key_exists("mail", $info[$i])) {
            echo "primeiro email é: " . $info[$i]["mail"][0] . "<br />";
        }
        echo "<hr />";
    }

    echo "Fechando a conexão";
    ldap_close($ds);

} else {
    echo "<h4>Não foi possível conectar ao servidor LDAP</h4>";
}

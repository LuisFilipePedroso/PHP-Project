<?php 

class Minerator {

    public function minerate($code) {
        $page = file_get_contents('http://urionlinejudge.com.br/judge/en/profile/' . $code);
        $arr1 = explode("<div class=\"pb-username\">", $page);
        $arr2 = explode("</ul>", $arr1[1]);

        $nome1 = explode($code."\">", $arr2[0]);
        $nome = explode("</a>",$nome1[1])[0];

        $rankGeral1 = explode("Place:</span>",$nome1[1]);
        $rankGeral = explode("&", $rankGeral1[1])[0];
        $rankGeral = str_replace(",", ".", $rankGeral);
        $rankGeral = trim($rankGeral);

        $instituicao1 = explode("'name'>", $rankGeral1[1]); 
        $instituicao = explode("</i>", $instituicao1[1])[0];

        $data1 = explode("Since:</span>", $instituicao1[1]);
        $data = trim(explode("</li>", $data1[1])[0]); 

        $score1 = explode("Points:</span>", $data1[1]);
        $score = trim(explode("</li>", $score1[1])[0]); 

        $resolvidos1 = explode("Solved:</span>", $score1[1]);
        $resolvidos = trim(explode("</li>", $resolvidos1[1])[0]); 

        $tentados1 = explode("Tried:</span>", $resolvidos1[1]);
        $tentados = trim(explode("</li>", $tentados1[1])[0]); 

        $submetidos1 = explode("Submissions:</span>", $tentados1[1]);
        $submetidos = trim(explode("</li>", $submetidos1[1])[0]); 

        $data = [
            'name' => $nome,
            'generalRank' => $rankGeral,
            'institution' => $instituicao,
            'signedSince' => $data,
            'score' => $score,
            'solved' => $resolvidos,
            'tried' => $tentados,
            'submissions' => $submetidos 
        ];

        return $data;
    }
}
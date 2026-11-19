<?php
class Jogo{
    //atributos
    private $titulo;
    private $genero;
    private $plataforma;
    private $ano;

    //métodos
    public function __construct($titulo, $genero, $plataforma, $ano)
    {
        $this->titulo = $titulo;
        $this->genero = $genero;
        $this->plataforma = $plataforma;
        $this->ano = $ano;
    }

    /**
     * Regras de negocio da entidade.
     * Retorna um array de erros (vazio = dados validos).
     */
    public static function validar($dados)
    {
        $erros = [];

        $titulo = trim($dados['titulo'] ?? '');
        $genero = trim($dados['genero'] ?? '');
        $plataforma = trim($dados['plataforma'] ?? '');
        $ano = $dados['ano'] ?? '';

        if ($titulo === '') {
            $erros[] = 'O título é obrigatório.';
        } elseif (mb_strlen($titulo) > 255) {
            $erros[] = 'O título deve ter no máximo 255 caracteres.';
        }

        if ($genero === '') {
            $erros[] = 'O gênero é obrigatório.';
        } elseif (mb_strlen($genero) > 255) {
            $erros[] = 'O gênero deve ter no máximo 255 caracteres.';
        }

        if ($plataforma === '') {
            $erros[] = 'A plataforma é obrigatória.';
        } elseif (mb_strlen($plataforma) > 255) {
            $erros[] = 'A plataforma deve ter no máximo 255 caracteres.';
        }

        $anoAtual = (int) date('Y');
        if (!filter_var($ano, FILTER_VALIDATE_INT, [
            'options' => ['min_range' => 1950, 'max_range' => $anoAtual + 1]
        ])) {
            $erros[] = 'O ano deve ser um número inteiro entre 1950 e ' . ($anoAtual + 1) . '.';
        }

        return $erros;
    }

    /** Valida chaves primárias recebidas pela URL. */
    public static function validarId($id)
    {
        return filter_var($id, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]) !== false;
    }

    /**
     * Get the value of titulo
     */
    public function getTitulo()
    {
        return $this->titulo;
    }

    /**
     * Get the value of genero
     */
    public function getGenero()
    {
        return $this->genero;
    }

    /**
     * Get the value of plataforma
     */
    public function getPlataforma()
    {
        return $this->plataforma;
    }

    /**
     * Get the value of ano
     */
    public function getAno()
    {
        return $this->ano;
    }
}
?>

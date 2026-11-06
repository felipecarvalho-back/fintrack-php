<?php

declare(strict_types=1);

namespace App\Entity;

use App\Enum\TipoCalculo;
use App\Enum\TipoVariavel;

class VariavelOrcamento
{
    public function __construct(
        private ?int $id,
        private int $contaId,
        private string $nome,
        private TipoVariavel $tipo,
        private bool $isFixo,
        private float $valorPadrao,
        private int $ordem = 0,
        private TipoCalculo $tipoCalculo = TipoCalculo::Simples,
        private ?string $labelMultiplicador = 'Dias',
        private ?string $mesesAplicaveis = null,
        private ?int $ano = null
    ) {}

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContaId(): int
    {
        return $this->contaId;
    }

    public function getNome(): string
    {
        return $this->nome;
    }

    public function getTipo(): TipoVariavel
    {
        return $this->tipo;
    }

    public function getTipoCalculo(): TipoCalculo
    {
        return $this->tipoCalculo;
    }

    public function getLabelMultiplicador(): ?string
    {
        return $this->labelMultiplicador;
    }

    public function isFixo(): bool
    {
        return $this->isFixo;
    }

    public function getIsFixo(): bool
    {
        return $this->isFixo;
    }

    public function getValorPadrao(): float
    {
        return $this->valorPadrao;
    }

    public function getOrdem(): int
    {
        return $this->ordem;
    }

    public function getMesesAplicaveis(): ?string
    {
        return $this->mesesAplicaveis;
    }

    public function getAno(): ?int
    {
        return $this->ano;
    }

    /**
     * Retorna um array com os números dos meses (1 a 12) onde a variável se aplica.
     */
    public function getMesesArray(): array
    {
        if ($this->mesesAplicaveis === null || trim($this->mesesAplicaveis) === '') {
            return range(1, 12);
        }

        $partes = explode(',', $this->mesesAplicaveis);
        $meses = [];
        foreach ($partes as $p) {
            $m = (int) trim($p);
            if ($m >= 1 && $m <= 12) {
                $meses[] = $m;
            }
        }
        sort($meses);

        return !empty($meses) ? array_values(array_unique($meses)) : range(1, 12);
    }

    /**
     * Verifica se a variável deve ser exibida/aplicada no mês informado (1 a 12).
     */
    public function aplicaNoMes(int $mes): bool
    {
        return in_array($mes, $this->getMesesArray(), true);
    }

    /**
     * Verifica se a variável se aplica considerando tanto o mês quanto o ano especificado.
     */
    public function aplicaNoMesEAno(int $mes, int $ano): bool
    {
        if ($this->ano !== null && $this->ano > 0 && $this->ano !== $ano) {
            return false;
        }

        return $this->aplicaNoMes($mes);
    }

    /**
     * Retorna a descrição amigável dos meses aplicáveis (ex: "Todos os meses", "Jan, Fev" ou "Novembro/2026").
     */
    public function getMesesFormatados(): string
    {
        $meses = $this->getMesesArray();
        $strAno = ($this->ano !== null && $this->ano > 0) ? " / {$this->ano}" : '';

        if (count($meses) === 12) {
            return ($this->ano !== null && $this->ano > 0) ? "Todos os meses de {$this->ano}" : 'Todos os meses';
        }

        $siglas = [
            1 => 'Jan', 2 => 'Fev', 3 => 'Mar', 4 => 'Abr',
            5 => 'Mai', 6 => 'Jun', 7 => 'Jul', 8 => 'Ago',
            9 => 'Set', 10 => 'Out', 11 => 'Nov', 12 => 'Dez'
        ];

        $res = [];
        foreach ($meses as $m) {
            if (isset($siglas[$m])) {
                $res[] = $siglas[$m];
            }
        }

        return !empty($res) ? implode(', ', $res) . $strAno : 'Todos os meses';
    }

    public function toArray(): array
    {
        return [
            'id'                 => $this->id,
            'contaId'            => $this->contaId,
            'nome'               => $this->nome,
            'tipo'               => $this->tipo->value,
            'tipoCalculo'        => $this->tipoCalculo->value,
            'labelMultiplicador' => $this->labelMultiplicador,
            'isFixo'             => $this->isFixo,
            'mesesAplicaveis'    => $this->mesesAplicaveis,
            'ano'                => $this->ano,
            'mesesArray'         => $this->getMesesArray(),
            'mesesFormatados'    => $this->getMesesFormatados(),
            'valorPadrao'        => $this->valorPadrao,
            'ordem'              => $this->ordem,
        ];
    }
}

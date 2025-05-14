<?php

namespace App\Models;

use App\Models\UsuarioModel;

class CompradorModel extends UsuarioModel
{
    /**
     * Filtra somente os registro não excluidos
     */
    protected function getFilters()
    {
        parent::getFilters();
        $this->where('usuarios.usuario_interno', 'N');
    }

    public function getCompradores()
    {
        $builder = $this->db->table('usuarios u');
        $builder->select('u.username, u.nome_completo, u.email_principal, u.celular, x.situacao, x.situacao');
        $builder->join('(select distinct comprador, \'A\' as situacao
                            from cadastro_pessoa p
                            where p.estado_registro = \'ATIVO\'
                            and p.comprador is not null
                            union 
                            select distinct comprador, \'I\' as situacao
                            from compras c
                            where c.estado_registro = \'ATIVO\'
                            and c.movimentacao = \'COMPRA\'
                            and c.comprador not in (select distinct comprador 
                                        from cadastro_pessoa 
                                        where comprador is not null
                                        and estado_registro = \'ATIVO\')) x', 'x.comprador = u.username');
        $builder->where('u.estado_registro', 'ATIVO')->orderBy('u.nome_completo');
        $query = $builder->get();
        return $query->getResult();
    }


}

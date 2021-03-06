/****** Script do comando SelectTopNRows de SSMS  ******/
SELECT TOP 1000 [idPedido]
      ,[idMaterial]
      ,[largura]
      ,[altura]
      ,[quantidade]
      ,[localizacao]
      ,[data_entrega]
      ,[observacao]
      ,[Data_do_Pedido]
      ,[idUsuario]
      ,[status_pedido]
      ,[valorProduto]
      ,[valorTotal]
      ,[custeio]
      ,[idCompra]
      ,[formaPagamento]
      ,[segmento]
      ,[publicAlvo]
      ,[acao]
      ,[objetivo]
      ,[idLoja]
      ,[fotoArte]
  FROM [MARKETING].[dbo].[PedidoDMTRIX] where idCompra = 1031
  
  select p.dataCompra,p.valorTotal,
  case when p.titulo is null then 'Sem Titulo' else p.titulo end as Titulo ,p.idCompra,u.nome, 
  case when p.Prioridade is null then 0 
  else p.Prioridade end as prioridade from ComprasDMTRIX p 
  inner join usuariosDMTRIX u on u.idUsuario = p.idUsuario order by p.dataCompra desc -- todos os pedidos
  
  select p.idCompra, p.idPedido,p.idMaterial, p.largura,p.altura,p.quantidade,p.status_pedido, t.idUsuario as Criacao from tarefasDMTRIX t join 
  PedidoDMTRIX p on t.idPedido = p.idPedido where idCompra = 1031 and p.status_pedido = 5 --verificar quantos pedidos estão com criação para esta compra para barra de progresso(fabricação,estimativa,revisão,aprovação,fornecedor,disponivel para retirada,pedido entregue)
  
  
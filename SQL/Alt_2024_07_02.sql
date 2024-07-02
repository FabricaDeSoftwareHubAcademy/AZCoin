SELECT * FROM analise ORDER BY id_analise;
SELECT * FROM campanha ORDER BY id_campanha;
SELECT * FROM carteira ORDER BY id_carteira;
SELECT * FROM feedback ORDER BY id_feedback;
SELECT * FROM perfil_usuario;
SELECT * FROM produto ORDER BY id_produto;
SELECT * FROM status_feedback;
SELECT * FROM status_produto;
SELECT * FROM status_usuario;
SELECT * FROM troca_produto;
SELECT * FROM usuario ORDER BY id_usuario;

UPDATE produto SET nome = 'Fone Pro Pro' WHERE id_produto = 5;
UPDATE produto SET qde_produto = '20' WHERE id_produto = 4;
UPDATE produto SET nome = 'DMC Delorean', valor_produto = '1000' WHERE id_produto = 7;


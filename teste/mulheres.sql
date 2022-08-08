SELECT DISTINCT ON (nu_cns, nu_cpf)
	tb5.no_cidadao,
	tb5.dt_nascimento,
	tb5.no_sexo,
	tb5.nu_cns,
	tb5.nu_cpf,
	tb5.nu_micro_area,
	tb5.nu_ine,
	tb5.no_equipe,
	tb5.nu_cnes,
	tb5.no_unidade_saude
FROM
(
	SELECT
		tb4.*,
		tb_dim_unidade_saude.nu_cnes,
		tb_dim_unidade_saude.no_unidade_saude
	FROM
	(
		SELECT
			tb3.*,
			tb_dim_equipe.nu_ine,
			tb_dim_equipe.no_equipe
		FROM
		(
			SELECT
				tb1.*,
				tb2.nu_micro_area,
				tb2.co_dim_unidade_saude,
				tb2.co_dim_equipe
			FROM
			(
				SELECT 
					no_cidadao,
					dt_nascimento,
					no_sexo,
					nu_cns,
					nu_cpf
				FROM 
					tb_cidadao
				WHERE
					no_sexo = 'FEMININO' AND
					st_ativo = 1
			) AS tb1
			INNER JOIN
			(
				SELECT DISTINCT ON (nu_cns, nu_cpf_cidadao) 
					nu_cns,
					nu_cpf_cidadao,
					co_dim_unidade_saude,
					co_dim_equipe,
					co_dim_tempo,
					nu_micro_area
				FROM 
					tb_fat_cad_individual
				WHERE
					st_ficha_inativa = 0
				ORDER BY nu_cns, nu_cpf_cidadao, co_dim_tempo DESC
			) AS tb2
			ON
			CASE 
				WHEN length(tb2.nu_cns) = 15 THEN 
					tb2.nu_cns = tb1.nu_cns 
				ELSE 
					CASE 
						WHEN length(tb2.nu_cpf_cidadao) = 11 THEN 
							tb2.nu_cpf_cidadao = tb1.nu_cpf 
						ELSE 
							false 
					END 
			END
		) AS tb3
		LEFT JOIN
			tb_dim_equipe
		ON tb_dim_equipe.co_seq_dim_equipe = tb3.co_dim_equipe
	) AS tb4
	LEFT JOIN
		tb_dim_unidade_saude
	ON tb_dim_unidade_saude.co_seq_dim_unidade_saude = tb4.co_dim_unidade_saude
) AS tb5;
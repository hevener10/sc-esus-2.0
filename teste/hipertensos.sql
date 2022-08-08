-- Numerador indicador 6 - hipertensos
-- (09) Join para cadastro individual --------------------------
SELECT 
	tb8.*,
	CASE WHEN tb9.st_hipertensao_arterial IS NULL THEN '0'
		ELSE tb9.st_hipertensao_arterial END
		hci,
	CASE WHEN tb9.st_diabete IS NULL THEN '0'
		ELSE tb9.st_diabete END
		dci,
	tb9.nu_micro_area
FROM
(
	-- rapido ---------------------------------------------------
	-- (07) Join para nome ----------
	SELECT
		tb6.*,
		tb7.no_cidadao,
		tb7.dt_nascimento,
		tb7.no_sexo
	FROM
	(
		-- (06) Tira duplicados ----------
		SELECT DISTINCT ON (nu_cns, nu_cpf_cidadao)
			nu_cns,
			nu_cpf_cidadao,
			co_dim_tempo,
			ds_filtro_proced_avaliados,
			nu_ine,
			no_equipe,
			nu_cnes,
			no_unidade_saude,
			nu_cbo
		FROM
		(
			-- (04) Join para cbo ----------
			SELECT 
				*
			FROM
			(
				-- (03) Join para unidade ----------
				SELECT 
					*
				FROM
				(
					-- (02) Join para equipe ----------
					SELECT 
						*
					FROM
					(
						-- (01) Hipertensos --------------
						SELECT DISTINCT ON (nu_cns, nu_cpf_cidadao) 
							CASE WHEN nu_cns IS NULL THEN '0'
								ELSE nu_cns END
							nu_cns,
							CASE WHEN nu_cpf_cidadao IS NULL THEN '0'
								ELSE nu_cpf_cidadao END
							nu_cpf_cidadao,
							CASE WHEN co_dim_cbo_1 = 1 THEN co_dim_cbo_2
								ELSE co_dim_cbo_1 END
							co_dim_cbo,
							CASE WHEN co_dim_unidade_saude_1 = 1 THEN co_dim_unidade_saude_2
								ELSE co_dim_unidade_saude_1 END
							co_dim_unidade_saude,
							CASE WHEN co_dim_equipe_1 = 1 THEN co_dim_equipe_2
								ELSE co_dim_equipe_1 END
							co_dim_equipe,			
							co_dim_tempo,
							ds_filtro_proced_avaliados
						FROM
							tb_fat_atendimento_individual
						WHERE
							-- dataI e dataF
							-- (co_dim_tempo >= 20200701 AND co_dim_tempo <= 20201231) AND
							(
								ds_filtro_ciaps LIKE ANY (
									array[
										'%|K86|%',
										'%|K87|%',
										'%|W81|%'
									]
								) 
								OR
								ds_filtro_cids LIKE ANY (
									array[
										'%|I10|%',
										'%|I11|%',
										'%|I110|%',
										'%|I119|%',
										'%|I12|%',
										'%|I120|%',
										'%|I129|%',
										'%|I13|%',
										'%|I130|%',
										'%|I131|%',
										'%|I132|%',
										'%|I139|%',
										'%|I15|%',
										'%|I150|%',
										'%|I151|%',
										'%|I152|%',
										'%|I158|%',
										'%|I159|%',
										'%|I270|%',
										'%|I272|%',
										'%|O10|%',
										'%|O100|%',
										'%|O101|%',
										'%|O102|%',
										'%|O103|%',
										'%|O104|%',
										'%|O109|%'
									]
								)
							)
						ORDER BY nu_cns, nu_cpf_cidadao, co_dim_tempo DESC
						-- (01) ----------------------------------------------------------------
					) AS tb1
					LEFT JOIN
						tb_dim_equipe
					ON tb_dim_equipe.co_seq_dim_equipe = tb1.co_dim_equipe
					-- (02) ---------------------------------------------------------------
				) AS tb2
				LEFT JOIN
					tb_dim_unidade_saude
				ON tb_dim_unidade_saude.co_seq_dim_unidade_saude = tb2.co_dim_unidade_saude
				-- (03) ---------------------------------------------------------------
			) AS tb3
			LEFT JOIN
				tb_dim_cbo
			ON tb3.co_dim_cbo = tb_dim_cbo.co_seq_dim_cbo
			-- (04) ---------------------------------------------------------------
		) AS tb5
		-- (06) ---------------------------------------------------------------
	) AS tb6
	INNER JOIN 
	(
		SELECT 
			* 
		FROM 
			tb_cidadao
		WHERE
			st_ativo = 1
	) AS tb7
	ON 
	CASE 
		WHEN length(tb6.nu_cns) = 15 THEN 
			tb6.nu_cns = tb7.nu_cns 
		ELSE 
			CASE 
				WHEN length(tb6.nu_cpf_cidadao) = 11 THEN 
					tb6.nu_cpf_cidadao = tb7.nu_cpf 
				ELSE 
					false 
			END 
	END
	-- (07) ---------------------------------------------------------------
	-- rapido ---------------------------------------------------
) AS tb8
LEFT JOIN
(
	-- (08) Dados do cadastro individual -------------------
	SELECT DISTINCT ON (nu_cns, nu_cpf_cidadao)
		nu_cns,
		nu_cpf_cidadao,
		co_dim_tempo,
		st_hipertensao_arterial,
		st_diabete,
		nu_micro_area
	FROM 
		tb_fat_cad_individual
	ORDER BY nu_cns, nu_cpf_cidadao, co_dim_tempo DESC
	-- (08) ---------------------------------------------------------------
) AS tb9
ON
CASE 
	WHEN length(tb9.nu_cns) = 15 THEN 
		tb9.nu_cns = tb8.nu_cns 
	ELSE 
		CASE 
			WHEN length(tb9.nu_cpf_cidadao) = 11 THEN 
				tb9.nu_cpf_cidadao = tb8.nu_cpf_cidadao 
			ELSE 
				false 
		END 
END
-- (09) ---------------------------------------------------------------
ORDER BY nu_cns, nu_cpf_cidadao










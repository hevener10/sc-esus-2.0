
-- (10) busca cns ou cpf -------------------------------
SELECT 
	*
FROM
(
	-- (07) join cbo -------------------------------
	SELECT
		t7.nu_cns,
		t7.nu_cpf_cidadao,
		t7.co_dim_tempo,
		t7.co_proced,
		tb_dim_cbo.nu_cbo,
		t7.tabela
	FROM
	(
		-- (09) uniao 06 e 08 -------------------------------
		SELECT 
			*
		FROM 
		(
			-- (06)join procedimento -------------------------------
			SELECT 
				t6.*,
				tb_dim_procedimento.co_proced
			FROM
			(
				-- (05) uniao 03 e 04 -------------------------------
				SELECT
					*
				FROM 
				(
					-- (03) uniao 01 e 02 -------------------------------
					SELECT 
						*
					FROM 
					(
						-- (01) tabela tb_fat_proced_atend_proced -------------------------------
						SELECT DISTINCT ON (nu_cns, nu_cpf_cidadao)
						CASE WHEN nu_cns IS NULL THEN '0'
							ELSE nu_cns END
						nu_cns,
						CASE WHEN nu_cpf_cidadao IS NULL THEN '0'
							ELSE nu_cpf_cidadao END
						nu_cpf_cidadao,
							co_dim_tempo, 
							co_dim_procedimento,
							co_dim_cbo,
							text 'tb_fat_proced_atend_proced' as tabela
						FROM 
							tb_fat_proced_atend_proced
						WHERE
							(co_dim_tempo >= ".datasomadias(datasomameses($dataF,12,'-'),1)." AND co_dim_tempo <= ".$dataF.") AND
						-- (01) ---------------------------------------------------------------------
					) AS t1
					UNION ALL
					SELECT 
						* 
					FROM 
					(
						-- (02) tabela tb_fat_atd_ind_procedimentos -------------------------------
						SELECT
							CASE WHEN nu_cns IS NULL THEN '0'
								ELSE nu_cns END
							nu_cns,
							CASE WHEN nu_cpf_cidadao IS NULL THEN '0'
								ELSE nu_cpf_cidadao END
							nu_cpf_cidadao,
							co_dim_tempo,
							CASE WHEN co_dim_procedimento_avaliado = 1 THEN co_dim_procedimento_solicitado
								ELSE co_dim_procedimento_avaliado END
							co_dim_procedimento,
							CASE WHEN co_dim_cbo_1 = 1 THEN co_dim_cbo_2
								ELSE co_dim_cbo_1 END
							co_dim_cbo,
							text 'tb_fat_atd_ind_procedimentos' as tabela
						FROM 
							tb_fat_atd_ind_procedimentos
						WHERE
							(co_dim_tempo >= ".datasomadias(datasomameses($dataF,12,'-'),1)." AND co_dim_tempo <= ".$dataF.") AND
						-- (02) ---------------------------------------------------------------------
					) AS t2
					-- (03) ---------------------------------------------------------------------
				) AS t3
				UNION ALL
				SELECT 
					* 
				FROM 
				(
					-- (04) tabela tb_fat_atend_odonto_proced -------------------------------
					SELECT
						CASE WHEN nu_cns IS NULL THEN '0'
							ELSE nu_cns END
						nu_cns,
						CASE WHEN nu_cpf_cidadao IS NULL THEN '0'
							ELSE nu_cpf_cidadao END
						nu_cpf_cidadao,
						co_dim_tempo,
						co_dim_procedimento, 
						CASE WHEN co_dim_cbo_1 = 1 THEN co_dim_cbo_2
							ELSE co_dim_cbo_1 END
						co_dim_cbo,
						text 'tb_fat_atend_odonto_proced' as tabela
					FROM 
						tb_fat_atend_odonto_proced
					WHERE
						(co_dim_tempo >= ".datasomadias(datasomameses($dataF,12,'-'),1)." AND co_dim_tempo <= ".$dataF.") AND
					-- (04) ---------------------------------------------------------------------
				) AS t4
				-- (05) ---------------------------------------------------------------------
			) AS t6
			LEFT JOIN
				tb_dim_procedimento
			ON tb_dim_procedimento.co_seq_dim_procedimento = t6.co_dim_procedimento
			-- (06) ---------------------------------------------------------------------
		) AS t8
		UNION ALL
		SELECT 
			* 
		FROM 
		(
			-- (08) tabela tb_fat_proced_atend -------------------------------
			SELECT
				CASE WHEN nu_cns IS NULL THEN '0'
					ELSE nu_cns END
				nu_cns,
				CASE WHEN nu_cpf_cidadao IS NULL THEN '0'
					ELSE nu_cpf_cidadao END
				nu_cpf_cidadao,
				co_dim_tempo,
				int '0' as co_dim_procedimento,
				co_dim_cbo,
				text 'tb_fat_proced_atend' as tabela,
				text '0301100039' as co_proced
			FROM
				tb_fat_proced_atend
			WHERE
				(co_dim_tempo >= ".datasomadias(datasomameses($dataF,12,'-'),1)." AND co_dim_tempo <= ".$dataF.") AND
				ds_filtro_procedimento LIKE '%|0301100039|%'
			-- (08) ---------------------------------------------------------------------
		) AS t9
		-- (09) ---------------------------------------------------------------------
	) AS t7
	LEFT JOIN
		tb_dim_cbo
	ON tb_dim_cbo.co_seq_dim_cbo = t7.co_dim_cbo
	-- (07) ---------------------------------------------------------------------
) AS t10
WHERE
	".$busca_campo." = '".$busca_valor."' AND
	(co_dim_tempo >= ".datasomadias(datasomameses($dataF,12,'-'),1)." AND co_dim_tempo <= ".$dataF.") AND
	co_proced = '0301100039' AND
	nu_cbo LIKE ANY (array['2251%','2252%','2253%','2231%','2235%','3222%'])
LIMIT 1
-- (10) ---------------------------------------------------------------------

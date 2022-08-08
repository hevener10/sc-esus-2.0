-- $proced_citop = "'0201020033','ABPG010'";
-- $c_ano = (int) substr($dataI,0,4);
-- $c_ano = $c_ano - 64;
-- $NdataI = $c_ano.substr($dataI,-4);
-- $NdataIF = substr($NdataI,0,4)."-".substr($NdataI,4,2)."-".substr($NdataI,6,2);
-- $c_ano = (int) substr($dataF,0,4);
-- $c_ano = $c_ano - 25;
-- $NdataF = $c_ano.substr($dataF,-4);
-- $NdataFF = substr($NdataF,0,4)."-".substr($NdataF,4,2)."-".substr($NdataF,6,2);
-- $c_ano = (int) substr($dataI,0,4);
-- $c_ano = $c_ano - 3;
-- $NdataIF3 = $c_ano.substr($dataI,-4);

SELECT DISTINCT ON (nu_cns, nu_cpf) *
FROM
(
	SELECT
		tb8.*,
		tb9.nu_ine,
		tb9.no_equipe,
		tb9.nu_cnes,
		tb9.no_unidade_saude
	FROM
	(
	
	
	
	
	
		-- estava rapido ate aqui ------------------------------------------
		SELECT DISTINCT ON (nu_cns, nu_cpf) *
		FROM
		(
			SELECT 
				tb5.*,
				CASE WHEN tb6.nu_cns IS NULL THEN '0'
					ELSE '1' END
				tb_fat_atd_ind_procedimentos
			FROM
			(
				SELECT 
					tb3.*,
					CASE WHEN tb4.nu_cns IS NULL THEN '0'
						ELSE '1' END
					tb_fat_atend_odonto_proced
				FROM
				(
					SELECT
						tb1.*,
						CASE WHEN tb2.nu_cns IS NULL THEN '0'
							ELSE '1' END
						tb_fat_proced_atend_proced
					FROM
					(
						SELECT DISTINCT ON (nu_cns, nu_cpf)
							CASE WHEN nu_cns IS NULL THEN '0'
								ELSE nu_cns END
							nu_cns,
							CASE WHEN nu_cpf IS NULL THEN '0'
								ELSE nu_cpf END
							nu_cpf,
							dt_nascimento,
							no_cidadao,
							nu_area, 
							nu_micro_area, 
							st_fora_area
						FROM 
							tb_cidadao
						WHERE
							-- $NdataIF e $NdataFF
							(dt_nascimento >= '1956-09-01' AND dt_nascimento <= '1995-12-31') AND
							no_sexo = 'FEMININO' AND
							st_ativo = 1 AND
							dt_obito IS NULL
						ORDER BY nu_cns, nu_cpf
					) AS tb1
					LEFT JOIN
					(
						SELECT 
							nu_cns,
							nu_cpf_cidadao
						FROM 
							tb_fat_proced_atend_proced 
						WHERE 
							-- $NdataIF3 e $dataF
							(co_dim_tempo >= 20170901 AND co_dim_tempo <= 20201231) 
							AND co_dim_procedimento 
							IN 
							(
								SELECT 
									co_seq_dim_procedimento 
								FROM 
									tb_dim_procedimento 
								WHERE 
									co_proced 
								IN ('0201020033','ABPG010')
							) 
					) AS tb2
					ON 
					CASE WHEN length(tb2.nu_cns) = 15 THEN tb2.nu_cns = tb1.nu_cns ELSE CASE WHEN length(tb2.nu_cpf_cidadao) = 11 THEN tb2.nu_cpf_cidadao = tb1.nu_cpf ELSE false END END
				) AS tb3
				LEFT JOIN
				(
					SELECT 
						nu_cns,
						nu_cpf_cidadao
					FROM 
						tb_fat_atend_odonto_proced 
					WHERE 
						-- $NdataIF3 e $dataF
						(co_dim_tempo >= 20170901 AND co_dim_tempo <= 20201231)  
						AND co_dim_procedimento 
						IN 
						(
							SELECT 
								co_seq_dim_procedimento 
							FROM 
								tb_dim_procedimento 
							WHERE 
								co_proced 
							IN ('0201020033','ABPG010')
						) 
				) AS tb4
				ON 
				CASE WHEN length(tb4.nu_cns) = 15 THEN tb4.nu_cns = tb3.nu_cns ELSE CASE WHEN length(tb4.nu_cpf_cidadao) = 11 THEN tb4.nu_cpf_cidadao = tb3.nu_cpf ELSE false END END
			) AS tb5
			LEFT JOIN
			(
				SELECT 
					nu_cns,
					nu_cpf_cidadao
				FROM 
					tb_fat_atd_ind_procedimentos
				WHERE
					-- $NdataIF3 e $dataF
					(co_dim_tempo >= 20170901 AND co_dim_tempo <= 20201231) 
					AND 
					(
						co_dim_procedimento_avaliado
						IN (
							SELECT 
								co_seq_dim_procedimento 
							FROM 
								tb_dim_procedimento 
							WHERE 
								co_proced IN ('0201020033','ABPG010')
						) OR co_dim_procedimento_solicitado
						IN 
						(
							SELECT 
								co_seq_dim_procedimento 
							FROM 
								tb_dim_procedimento 
							WHERE 
								co_proced IN ('0201020033','ABPG010')
						) 
					)
			) AS tb6
			ON
			CASE WHEN length(tb6.nu_cns) = 15 THEN tb6.nu_cns = tb5.nu_cns ELSE CASE WHEN length(tb6.nu_cpf_cidadao) = 11 THEN tb6.nu_cpf_cidadao = tb5.nu_cpf ELSE false END END
		) AS tb7
		-- estava rapido ate aqui -------------------------------------------------
		
		
		
	) AS tb8
	LEFT JOIN
	(	
		SELECT 
			tb12.*,
			tb_dim_unidade_saude.nu_cnes,
			tb_dim_unidade_saude.no_unidade_saude
		FROM
		(
			SELECT 
				tb11.*,
				tb_dim_equipe.nu_ine,
				tb_dim_equipe.no_equipe
			FROM
			(
				SELECT DISTINCT ON (nu_cns, nu_cpf_cidadao)
					nu_cns,
					nu_cpf_cidadao,
					co_dim_equipe,
					co_dim_unidade_saude
				FROM 
					tb_fat_cad_individual
				ORDER BY nu_cns, nu_cpf_cidadao, co_dim_tempo DESC
			) AS tb11
			LEFT JOIN
				tb_dim_equipe
			ON tb_dim_equipe.co_seq_dim_equipe = tb11.co_dim_equipe
		) AS tb12
		LEFT JOIN
			tb_dim_unidade_saude
		ON tb_dim_unidade_saude.co_seq_dim_unidade_saude = tb12.co_dim_unidade_saude
	) AS tb9
	ON
	CASE WHEN length(tb9.nu_cns) = 15 THEN tb9.nu_cns = tb8.nu_cns ELSE CASE WHEN length(tb9.nu_cpf_cidadao) = 11 THEN tb9.nu_cpf_cidadao = tb8.nu_cpf ELSE false END END
) AS tb10





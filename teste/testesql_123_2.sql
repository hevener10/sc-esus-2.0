-- Todas as gestantes com DDP em Q3/2020
-- v3

-- (14) Remove duplicados --------------
SELECT DISTINCT ON (nu_cns, nu_cpf) 
	*
FROM
(
	-- (13) Mulheres com interrupcao clinica no quadrimestre --------------
	SELECT 
		tb8.*,
		CASE WHEN tb9.nu_cns IS NULL THEN '0'
			ELSE '1' END
			interrupcao
	FROM 
	(
		-- (09) Mulheres autodeclaradas gestantes no atendimento odontologico --------------
		SELECT 
			tb6.*,
			CASE WHEN tb7.nu_cns IS NULL THEN '0'
				ELSE tb7.st_gestante END
				autodeco
		FROM
		(
			-- (08) Mulheres autodeclaradas gestantes no cadastro individual --------------
			SELECT 
				tb4.*,
				CASE WHEN tb5.nu_cns IS NULL THEN '0'
					ELSE tb5.st_gestante END
					autodeck,
				tb5.nu_cnes,
				tb5.no_unidade_saude,
				tb5.nu_ine,
				tb5.no_equipe,
				tb5.nu_micro_area
			FROM
			(
				-- (07) Remove duplicados --------------
				SELECT DISTINCT ON (nu_cns, nu_cpf)
					*
				FROM
				(
					-- (06) Join que junta DUMs com dados do cidadao --------------
					SELECT
						CASE WHEN tb2.nu_cns IS NULL THEN '0'
							ELSE tb2.nu_cns END
							nu_cns,
						CASE WHEN tb2.nu_cpf IS NULL THEN '0'
							ELSE tb2.nu_cpf END
							nu_cpf,
						-- DdataF = datasomadias(dataF,294,'-');
						CASE WHEN tb1.co_dim_tempo_dum <= 20200312 THEN 'A'
							ELSE 'C' END
							condicao,
						tb1.co_dim_tempo,
						tb1.co_dim_tempo_dum,
						tb2.no_cidadao,
						tb2.dt_nascimento,
						tb2.no_sexo,
						tb2.nu_area
					FROM
					(
						-- (04) seleciona todas as mulheres com DUM que cairao no quadrimestre --------------
						SELECT DISTINCT ON (nu_cns, nu_cpf_cidadao) 
							*
						FROM
							tb_fat_atendimento_individual
						WHERE
							-- DdataI = datasomadias(dataI,294,'-');
							co_dim_tempo_dum >= 20191112 AND 
							-- 30001231
							co_dim_tempo_dum < 30001231
						ORDER BY nu_cns, nu_cpf_cidadao, co_dim_tempo_dum
						-- (04) ----------------------------------------------------------------
					) AS tb1
					INNER JOIN 
					(
						-- (05) seleciona dados do cidadao --------------
						SELECT  DISTINCT ON (nu_cns, nu_cpf)
							nu_cpf, 
							nu_cns,
							no_cidadao,
							dt_nascimento,
							no_sexo,
							nu_area
						FROM
							tb_cidadao
						WHERE
							st_faleceu = 0 AND 
							st_ativo = 1
						-- (05) ----------------------------------------------------------------
					) AS tb2 
					ON 
					CASE 
						WHEN length(tb1.nu_cns) = 15 THEN tb1.nu_cns = tb2.nu_cns 
						ELSE 
							CASE 
								WHEN length(tb1.nu_cpf_cidadao) = 11 THEN tb1.nu_cpf_cidadao = tb2.nu_cpf 
								ELSE 
									false 
								END 
						END
					-- (06) --------------------------------------------------------------
				) AS tb3
				ORDER BY nu_cns, nu_cpf, co_dim_tempo
				-- (07) --------------------------------------------------------------
			) AS tb4
			LEFT JOIN
			(	
				-- (01) JOIN para pegar as unidades --------------
				SELECT 
					tb02.nu_cns,
					tb02.nu_cpf_cidadao,
					tb02.co_dim_tempo,
					tb02.nu_micro_area,
					tb02.st_gestante,
					tb02.nu_ine,
					tb02.no_equipe,
					tb_dim_unidade_saude.nu_cnes,
					tb_dim_unidade_saude.no_unidade_saude
				FROM
				(
					-- (02) JOIN para pegar as equipes --------------
					SELECT
						tb01.*,		
						tb_dim_equipe.nu_ine, 
						tb_dim_equipe.no_equipe 
					FROM
					(
						-- (03) Gestantes autodeclaradas cadastro (K) --------------
						SELECT DISTINCT ON (nu_cns, nu_cpf_cidadao) 
							nu_cns,
							nu_cpf_cidadao,
							co_dim_unidade_saude,
							co_dim_equipe,
							co_dim_tempo,
							nu_micro_area,
							st_gestante
						FROM 
							tb_fat_cad_individual
						--WHERE 
							--st_gestante = 1 AND
							--(
							-- dataI
							--co_dim_tempo >= 20200901 AND 
							-- dataF
							--co_dim_tempo <= 20201231
							--)
						ORDER BY nu_cns, nu_cpf_cidadao, co_dim_tempo DESC
						-- (03) ---------------------------------------------------------
					) AS tb01
					LEFT JOIN
						tb_dim_equipe
					ON tb_dim_equipe.co_seq_dim_equipe = tb01.co_dim_equipe
					-- (02) ---------------------------------------------------------
				) AS tb02
				LEFT JOIN
					tb_dim_unidade_saude
				ON tb_dim_unidade_saude.co_seq_dim_unidade_saude = tb02.co_dim_unidade_saude
				-- (01) ---------------------------------------------------------
			) AS tb5
			ON 
			CASE 
				WHEN length(tb5.nu_cns) = 15 THEN 
					tb5.nu_cns = tb4.nu_cns 
				ELSE 
					CASE 
						WHEN length(tb5.nu_cpf_cidadao) = 11 THEN 
							tb5.nu_cpf_cidadao = tb4.nu_cpf 
						ELSE 
							false 
					END 
			END
			-- (08) ----------------------------------------------------------------
		) AS tb6
		LEFT JOIN
		(
			-- (11) Gestantes autodeclaradas odonto (O) --------------
			SELECT DISTINCT ON (nu_cns, nu_cpf_cidadao) *
			FROM 
				tb_fat_atendimento_odonto 
			WHERE 
				--st_gestante = 1 AND
				-- dataI e dataF
				(co_dim_tempo >= 20200901 AND co_dim_tempo <= 20201231)
			ORDER BY nu_cns, nu_cpf_cidadao, co_dim_tempo DESC
			-- (11) ----------------------------------------------------------------
		) AS tb7
		ON 
		CASE 
			WHEN length(tb7.nu_cns) = 15 THEN 
				tb7.nu_cns = tb6.nu_cns 
			ELSE 
				CASE 
					WHEN length(tb7.nu_cpf_cidadao) = 11 THEN 
						tb7.nu_cpf_cidadao = tb6.nu_cpf 
					ELSE 
						false 
				END 
		END
		-- (09) ----------------------------------------------------------------
	) AS tb8
	LEFT JOIN
	(
		-- (12) Gestantes com interrupcao clinica dentro do quadrimestre --------------
		SELECT DISTINCT ON (nu_cns, nu_cpf_cidadao) 
			*
		FROM
			tb_fat_atendimento_individual
		WHERE
			-- dataI e dataF
			(co_dim_tempo >= 20200901 AND co_dim_tempo <= 20201231)
			AND (
				ds_filtro_ciaps LIKE ANY (
					array[
						'%W82%',
						'%W83%',
						'%W90%',
						'%W91%',
						'%W92%',
						'%W93%'
					]
				) 
				OR
				ds_filtro_cids LIKE ANY (
					array[
						'%O42%',
						'%O45%',
						'%O60%',
						'%O61%',
						'%O62%',
						'%O63%',
						'%O64%',
						'%O65%',
						'%O66%',
						'%O67%',
						'%O68%',
						'%O69%',
						'%O70%',
						'%O71%',
						'%O73%',
						'%O750%',
						'%O751%',
						'%O754%',
						'%O755%',
						'%O756%',
						'%O757%',
						'%O758%',
						'%O759%',
						'%O81%',
						'%O82%',
						'%O83%',
						'%O84%',
						'%Z371%',
						'%Z373%',
						'%Z374%',
						'%Z376%',
						'%Z377%',
						'%Z379%',
						'%O42%',
						'%O45%',
						'%O60%',
						'%O61%',
						'%O62%',
						'%O63%',
						'%O64%',
						'%O65%',
						'%O66%',
						'%O67%',
						'%O68%',
						'%O69%',
						'%O70%',
						'%O71%',
						'%O73%',
						'%O750%',
						'%O751%',
						'%O754%',
						'%O755%',
						'%O756%',
						'%O757%',
						'%O758%',
						'%O759%',
						'%O81%',
						'%O82%',
						'%O83%',
						'%O84%',
						'%Z372%',
						'%Z375%',
						'%Z379%',
						'%Z38%',
						'%Z39%',
						'%Z371%',
						'%Z379%',
						'%O80%',
						'%Z370%',
						'%Z379%',
						'%Z38%',
						'%Z39%',
						'%O04%',
						'%Z303%',
						'%O02%',
						'%O03%',
						'%O05%',
						'%O06%'
					]
				)
			)
		-- (12) ----------------------------------------------------------------
	) AS tb9
	ON 
	CASE 
		WHEN length(tb9.nu_cns) = 15 THEN 
			tb9.nu_cns = tb8.nu_cns 
		ELSE 
			CASE 
				WHEN length(tb9.nu_cpf_cidadao) = 11 THEN 
					tb9.nu_cpf_cidadao = tb8.nu_cpf 
				ELSE 
					false 
			END 
	END
	-- (13) ----------------------------------------------------------------
) tb10
-- (14) ----------------------------------------------------------------
ORDER BY nu_cns, nu_cpf
tbca rest api
====

**GET /alimentos**<br>
Parâmetros: Nenhum<br>
Retorno: Lista de todos os alimentos


**GET /alimentos/[id]**<br>
Parâmetros: id<br>
Retorno: Alimento com id especificado


**GET /alimentos/[id]/categorias/**<br>
Parâmetros: id<br>
Retorno: Alimento com id especificado, incluindo sua categoria


**GET /alimentos/[id]/nutrientes/**<br>
Parâmetros: id<br>
Retorno: Alimento com id especificado, incluindo todos os seus nutrientes


**GET /alimentos/?nome=[nome]**<br>
Parâmetros: nome<br>
Retorno: Alimentos contendo, total ou parcialmente, o nome especificado


**GET /alimentos/?nutriente=[nome]&valor=[valor]&margem=[margem]**<br>
Parâmetros: nutriente, valor, margem (opcional, padrão: 5)<br>
Retorno: Alimentos contendo, total ou parcialmente, o nome especificado


**GET /categorias**<br>
Parâmetros: Nenhum<br>
Retorno: Lista de todos as categorias


**GET /categorias/[id]**<br>
Parâmetros: Nenhum<br>
Retorno: Categoria com id especificado
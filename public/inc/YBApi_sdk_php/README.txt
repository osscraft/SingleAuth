һ���ĵ�Ŀ¼�ṹ

-- /
  |
  |-- classes/      ����ƽ̨SDK��php��
  |
  |-- doc/          �ĵ�Ŀ¼
    |
    |-- api/        phpdoc���ɵ���api�ĵ�
    |
    |--
  |
  |-- examples/     ����ʵ��
    |
    |-- demo/       DEMO������Ŀ
      |
      |-- config.php      �����ļ�������Ҫ�޸�����ļ�д���Ӧ�� AppID ����Ϣ
      |
      |-- index.php       ʹ�ô��ļ����ʵ��
      |
      |-- authorize.php   ��Ȩ��֤����
      |
      |-- apicomm.php     ���ܽӿڲ��ԣ���Ҫ�����Ȩ���̻�ȡ��access_token���ܽ��нӿڲ��ԣ�
      |
      |-- frame.php       վ��Ӧ����Ȩʵ��
  |
  |-- README.txt          ���ĵ�


====================================================================

����SDK��Ҫ˵��

  1���� classes/ Ŀ¼�������ļ��ŵ�������Ŀ�У����������Ŀ¼�ṹ
  2��Ӧ�ó����а��� yb-globals.inc.php �ļ�
  3��ʵ���� YBOpenApi::getInstance() ����
  4������ʹ�õĽӿڣ����� init() �� bind() ����YBOpenApi��������Ϣ
  5������ getFrameUtil()��getAuthorize()��getUser()��getFriend() ����ʵ������Ӧ�ӿڶ���
  6�����ö�Ӧ����ķ�����ɽӿڷ���

============================================================

������ʾ��

  ����˵����
  $appid       Ӧ�õ�AppID
  $appsec      Ӧ�õ�AppSecret
  $callback    �ص���ַ����Ӧվ��Ӧ�õ�����վ��ַ��
  $token       access_token��������

  1��վ��Ӧ�ý���
    -------------------------------
  
    $info = YBOpenApi::getInstance()->init($appid, $appsec, $callback)->getFrameUtil()->perform();
  
    ---------------------------------------------------------------------------
  
    ֻ��Ҫ����һ�Σ����Զ��ض�����Ȩ������������Ȩ��֤��
    �����Ȩ�� $info �����з������Ƶ������Ϣ
  
  2��������Ȩ��֤����
    
    ��1�� $url = YBOpenApi::getInstance()->init($appid, $appsec, $callback)->getAuthorize()->forwardurl();
	
          -- ��ȡ��Ȩ��֤��ĵ�ַ���������Ҫ�ض��������ַ������Ȩ����
          
    ��2�� $info = YBOpenApi::getInstance()->init($appid, $appsec, $callback)->getAuthorize()->querytoken($_GET['code']);
	
          -- ����Ȩ���������غ�ͨ����Ȩ��codeֵ����ȡaccess_token��
		  
    ��3�������ӿڵ������ӣ��鿴 examples/demo/apicomm.php �ļ�
  
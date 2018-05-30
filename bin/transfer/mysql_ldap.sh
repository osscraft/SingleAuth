#!/bin/sh
ou="other"

if [ $4 = "教师" ]; then
    ou="teacher"
elif [ $4 = "学生" ]; then
    ou="student"
else 
    ou="other"
fi

if [ $1 = "delete" ]; then
    echo "uid=$2,ou=$ou,o=sso,dc=ldap,dc=lixin,dc=edu,dc=cn" > mysql_ldap/mysql-ldap.ldif
    ldapsearch -x -b "o=centerDB,dc=ldap,dc=lixin,dc=edu,dc=cn" "cn=$2" "dn" | grep "dn: cn=" | cut -c5- >> mysql_ldap/mysql-ldap.ldif
    exec ldapdelete -x -D "cn=Manager,dc=ldap,dc=lixin,dc=edu,dc=cn" -wpassword -f mysql_ldap/mysql-ldap.ldif
elif [ $1 = "add" ]; then
    password=$(php -r "echo '{md5}'.base64_encode(pack('H*', md5('$3')));")
    #echo $password
    echo "dn: userid=$2,ou=$ou,o=sso,dc=ldap,dc=lixin,dc=edu,dc=cn" > mysql_ldap/mysql-ldap.ldif
    echo "userid: $2" >> mysql_ldap/mysql-ldap.ldif
    echo "userPassword: $password" >> mysql_ldap/mysql-ldap.ldif
    echo "role: $4" >> mysql_ldap/mysql-ldap.ldif
    echo "username: $5" >> mysql_ldap/mysql-ldap.ldif
    echo "objectClass: top" >> mysql_ldap/mysql-ldap.ldif
    echo "objectClass: user" >> mysql_ldap/mysql-ldap.ldif
    echo "" >> mysql_ldap/mysql-ldap.ldif
    echo "dn: cn=$2,ou=024,o=centerDB,dc=ldap,dc=lixin,dc=edu,dc=cn" >> mysql_ldap/mysql-ldap.ldif
    echo "cn: $2" >> mysql_ldap/mysql-ldap.ldif
    echo "userPassword: $password" >> mysql_ldap/mysql-ldap.ldif
    echo "sn: $5" >> mysql_ldap/mysql-ldap.ldif
    echo "employeeType: $4" >> mysql_ldap/mysql-ldap.ldif
    echo "objectClass: top" >> mysql_ldap/mysql-ldap.ldif
    echo "objectClass: person" >> mysql_ldap/mysql-ldap.ldif
    echo "objectClass: organizationalPerson" >> mysql_ldap/mysql-ldap.ldif
    echo "objectClass: inetOrgPerson" >> mysql_ldap/mysql-ldap.ldif
    exec ldapadd -x -D "cn=Manager,dc=ldap,dc=lixin,dc=edu,dc=cn" -wpassword -f mysql_ldap/mysql-ldap.ldif -c
fi


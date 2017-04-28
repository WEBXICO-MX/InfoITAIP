/*==============================================================*/
/* DBMS name:      MySQL 5.0                                    */
/* Created on:     27/04/2017 10:43:05                          */
/*==============================================================*/


drop index index_1 on apartados;

drop table if exists apartados;

drop index index_1 on areas;

drop table if exists areas;

drop index index_1 on articulos;

drop table if exists articulos;

drop index index_1 on documentos;

drop index index_2 on documentos;

drop index index_3 on documentos;

drop table if exists documentos;

drop index index_1 on fracciones;

drop table if exists fracciones;

drop index index_1 on historial_cambio_contrasena;

drop index index_2 on historial_cambio_contrasena;

drop table if exists historial_cambio_contrasena;

drop index index_1 on incisos;

drop table if exists incisos;

drop index index_1 on permisos;

drop table if exists permisos;

drop index index_1 on usuarios;

drop index index_2 on usuarios;

drop table if exists usuarios;

/*==============================================================*/
/* Table: apartados                                             */
/*==============================================================*/
create table apartados
(
   cve_articulo         int not null,
   cve_fraccion         int not null,
   cve_inciso           int not null,
   cve_apartado         int not null,
   descripcion          varchar(500),
   activo               bool,
   primary key (cve_articulo, cve_fraccion, cve_inciso, cve_apartado)
);

/*==============================================================*/
/* Index: index_1                                               */
/*==============================================================*/
create index index_1 on apartados
(
   cve_articulo,
   cve_fraccion,
   cve_inciso,
   cve_apartado
);

/*==============================================================*/
/* Table: areas                                                 */
/*==============================================================*/
create table areas
(
   cve_area             int not null,
   descripcion          varchar(50),
   activo               bool,
   primary key (cve_area)
);

/*==============================================================*/
/* Index: index_1                                               */
/*==============================================================*/
create index index_1 on areas
(
   cve_area
);

/*==============================================================*/
/* Table: articulos                                             */
/*==============================================================*/
create table articulos
(
   cve_articulo         int not null,
   nombre               varchar(15),
   descripcion          varchar(300),
   activo               bool,
   primary key (cve_articulo)
);

/*==============================================================*/
/* Index: index_1                                               */
/*==============================================================*/
create index index_1 on articulos
(
   cve_articulo
);

/*==============================================================*/
/* Table: documentos                                            */
/*==============================================================*/
create table documentos
(
   cve_documento        int not null,
   cve_articulo         int,
   cve_fraccion         int,
   cve_inciso           int,
   cve_apartado         int,
   anio                 int not null,
   trimestre            int not null,
   nombre               varchar(100),
   fecha_actualizacion_documento datetime,
   ruta_documento       varchar(100),
   anexo                bool,
   cve_usuario          int,
   fecha_registro       datetime,
   cve_usuario2         int,
   fecha_modificacion   datetime,
   activo               bool,
   primary key (cve_documento)
);

/*==============================================================*/
/* Index: index_3                                               */
/*==============================================================*/
create index index_3 on documentos
(
   cve_articulo,
   cve_fraccion,
   cve_inciso,
   cve_apartado,
   anio,
   trimestre
);

/*==============================================================*/
/* Index: index_2                                               */
/*==============================================================*/
create index index_2 on documentos
(
   cve_documento
);

/*==============================================================*/
/* Index: index_1                                               */
/*==============================================================*/
create index index_1 on documentos
(
   anio,
   trimestre
);

/*==============================================================*/
/* Table: fracciones                                            */
/*==============================================================*/
create table fracciones
(
   cve_articulo         int not null,
   cve_fraccion         int not null,
   nombre               varchar(20),
   descripcion          varchar(600),
   activo               bool,
   primary key (cve_articulo, cve_fraccion)
);

/*==============================================================*/
/* Index: index_1                                               */
/*==============================================================*/
create index index_1 on fracciones
(
   cve_articulo,
   cve_fraccion
);

/*==============================================================*/
/* Table: historial_cambio_contrasena                           */
/*==============================================================*/
create table historial_cambio_contrasena
(
   cve_historial        int not null,
   cve_usuario          int,
   contrasena_nueva     varchar(12),
   contrasena_anterior  varchar(12),
   fecha_cambio         datetime,
   primary key (cve_historial)
);

/*==============================================================*/
/* Index: index_2                                               */
/*==============================================================*/
create index index_2 on historial_cambio_contrasena
(
   cve_usuario
);

/*==============================================================*/
/* Index: index_1                                               */
/*==============================================================*/
create index index_1 on historial_cambio_contrasena
(
   cve_historial
);

/*==============================================================*/
/* Table: incisos                                               */
/*==============================================================*/
create table incisos
(
   cve_articulo         int not null,
   cve_fraccion         int not null,
   cve_inciso           int not null,
   descripcion          varchar(500),
   activo               bool,
   primary key (cve_articulo, cve_fraccion, cve_inciso)
);

/*==============================================================*/
/* Index: index_1                                               */
/*==============================================================*/
create index index_1 on incisos
(
   cve_articulo,
   cve_fraccion,
   cve_inciso
);

/*==============================================================*/
/* Table: permisos                                              */
/*==============================================================*/
create table permisos
(
   cve_area             int not null,
   cve_articulo         int not null,
   cve_fraccion         int not null,
   cve_usuario          int,
   fecha_registro       datetime,
   cve_usuario2         int,
   fecha_modificacion   datetime,
   activo               bool,
   primary key (cve_area, cve_articulo, cve_fraccion)
);

/*==============================================================*/
/* Index: index_1                                               */
/*==============================================================*/
create index index_1 on permisos
(
   cve_area
);

/*==============================================================*/
/* Table: usuarios                                              */
/*==============================================================*/
create table usuarios
(
   cve_usuario          int not null,
   cve_area             int,
   nombre_completo      varchar(100),
   login                varchar(30),
   password             varchar(10),
   activo               bool,
   primary key (cve_usuario)
);

/*==============================================================*/
/* Index: index_2                                               */
/*==============================================================*/
create index index_2 on usuarios
(
   login,
   password
);

/*==============================================================*/
/* Index: index_1                                               */
/*==============================================================*/
create index index_1 on usuarios
(
   cve_usuario
);

alter table apartados add constraint FK_reference_6 foreign key (cve_articulo, cve_fraccion, cve_inciso)
      references incisos (cve_articulo, cve_fraccion, cve_inciso) on delete restrict on update restrict;

alter table documentos add constraint FK_reference_7 foreign key (cve_articulo, cve_fraccion, cve_inciso, cve_apartado)
      references apartados (cve_articulo, cve_fraccion, cve_inciso, cve_apartado) on delete restrict on update restrict;

alter table documentos add constraint FK_reference_8 foreign key (cve_usuario)
      references usuarios (cve_usuario) on delete restrict on update restrict;

alter table documentos add constraint FK_DOCUMENT_REFERENCE_USUARIOS2 foreign key (cve_usuario2)
      references usuarios (cve_usuario) on delete restrict on update restrict;

alter table fracciones add constraint FK_reference_4 foreign key (cve_articulo)
      references articulos (cve_articulo) on delete restrict on update restrict;

alter table historial_cambio_contrasena add constraint FK_reference_15 foreign key (cve_usuario)
      references usuarios (cve_usuario);

alter table incisos add constraint FK_reference_5 foreign key (cve_articulo, cve_fraccion)
      references fracciones (cve_articulo, cve_fraccion) on delete restrict on update restrict;

alter table permisos add constraint FK_reference_11 foreign key (cve_area)
      references areas (cve_area);

alter table permisos add constraint FK_reference_12 foreign key (cve_articulo, cve_fraccion)
      references fracciones (cve_articulo, cve_fraccion);

alter table permisos add constraint FK_reference_13 foreign key (cve_usuario)
      references usuarios (cve_usuario);

alter table permisos add constraint FK_PERMISOS_REFERENCE_USUARIOS2 foreign key (cve_usuario2)
      references usuarios (cve_usuario);

alter table usuarios add constraint FK_reference_10 foreign key (cve_area)
      references areas (cve_area) on delete restrict on update restrict;


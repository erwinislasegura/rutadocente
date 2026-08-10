<!doctype html>
<html lang="es">
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Tus datos de acceso | Ruta Docente</title></head>
<body style="margin:0;padding:0;background:#eef3f7;color:#102b46;font-family:Arial,Helvetica,sans-serif">
 <div style="display:none;max-height:0;overflow:hidden;opacity:0">Tu cuenta en Ruta Docente ya está disponible. Aquí encontrarás tus datos de acceso.</div>
 <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="width:100%;background:#eef3f7">
  <tr><td align="center" style="padding:24px 12px">
   <table role="presentation" width="600" cellspacing="0" cellpadding="0" border="0" style="width:100%;max-width:600px;overflow:hidden;border-radius:14px;background:#ffffff;box-shadow:0 10px 30px rgba(3,30,76,.10)">
    <tr><td style="padding:22px 26px;background:#031e4c;border-bottom:4px solid #e6b960">
     <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0"><tr>
      <td width="64" valign="middle"><img src="<?=e($logoUrl)?>" width="52" height="52" alt="Ruta Docente" style="display:block;width:52px;height:52px;object-fit:contain;border:0;background:#fff;border-radius:9px"></td>
      <td valign="middle"><div style="color:#fff;font-size:20px;font-weight:800;line-height:1.1">Ruta Docente</div><div style="margin-top:5px;color:#e6b960;font-size:10px;font-weight:700;letter-spacing:1.5px">PORTAFOLIO DOCENTE 2026</div></td>
     </tr></table>
    </td></tr>
    <tr><td style="padding:25px 26px 10px">
     <div style="color:#0752a3;font-size:10px;font-weight:800;letter-spacing:1.4px">CUENTA HABILITADA</div>
     <h1 style="margin:8px 0 7px;color:#102b46;font-size:23px;line-height:1.22">Hola, <?=e($user['first_name'])?>.</h1>
     <p style="margin:0;color:#607484;font-size:14px;line-height:1.55">Tu cuenta fue registrada correctamente. Usa las siguientes credenciales para ingresar a la plataforma y acceder a los recursos habilitados para tu perfil.</p>
    </td></tr>
    <tr><td style="padding:13px 26px">
     <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="border:1px solid #d9e4ee;border-radius:10px;background:#f7fafd">
      <tr><td colspan="2" style="padding:12px 15px 9px;border-bottom:1px solid #e2e9ef;color:#102b46;font-size:12px;font-weight:800">Datos personales</td></tr>
      <tr><td style="padding:11px 8px 5px 15px;color:#718493;font-size:11px">Nombre completo</td><td style="padding:11px 15px 5px 8px;text-align:right;color:#102b46;font-size:12px;font-weight:700"><?=e($user['first_name'].' '.$user['last_name'])?></td></tr>
      <tr><td style="padding:5px 8px 5px 15px;color:#718493;font-size:11px">Teléfono</td><td style="padding:5px 15px 5px 8px;text-align:right;color:#102b46;font-size:12px;font-weight:700"><?=e($user['phone']?:'No informado')?></td></tr>
      <tr><td style="padding:5px 8px 5px 15px;color:#718493;font-size:11px">Perfil</td><td style="padding:5px 15px 5px 8px;text-align:right;color:#102b46;font-size:12px;font-weight:700"><?=e(ucfirst($user['role']))?></td></tr>
      <tr><td style="padding:5px 8px 11px 15px;color:#718493;font-size:11px">Asignatura</td><td style="padding:5px 15px 11px 8px;text-align:right;color:#102b46;font-size:12px;font-weight:700"><?=e($user['subject']??'Sin asignatura')?></td></tr>
     </table>
    </td></tr>
    <tr><td style="padding:0 26px 13px">
     <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="border:1px solid #c9dced;border-radius:10px;background:#eef6fd">
      <tr><td style="padding:12px 15px 9px;color:#0752a3;font-size:11px;font-weight:800;letter-spacing:.5px">CREDENCIALES DE ACCESO</td></tr>
      <tr><td style="padding:0 15px 7px"><div style="color:#6e8190;font-size:10px">Correo electrónico</div><div style="margin-top:3px;color:#102b46;font-size:14px;font-weight:700;word-break:break-all"><?=e($user['email'])?></div></td></tr>
      <tr><td style="padding:3px 15px 13px"><div style="color:#6e8190;font-size:10px">Contraseña</div><div style="margin-top:4px;padding:9px 11px;border:1px dashed #a8c4de;border-radius:7px;background:#fff;color:#031e4c;font-family:Courier New,monospace;font-size:16px;font-weight:800;letter-spacing:.6px"><?=e($plainPassword)?></div></td></tr>
     </table>
    </td></tr>
    <tr><td style="padding:0 26px 14px">
     <div style="margin-bottom:10px;color:#607484;font-size:11px;line-height:1.5"><strong style="color:#102b46">Accesos habilitados:</strong> Tests <?=!empty($user['test_enabled'])?'✓':'—'?> &nbsp;·&nbsp; Tabuladores <?=!empty($user['tabulator_enabled'])?'✓':'—'?> &nbsp;·&nbsp; Cuenta <?=!empty($user['active'])?'activa':'inactiva'?></div>
     <table role="presentation" cellspacing="0" cellpadding="0" border="0" align="center"><tr><td align="center" bgcolor="#0752a3" style="border-radius:8px"><a href="<?=e($loginUrl)?>" style="display:inline-block;padding:12px 25px;color:#fff;font-size:13px;font-weight:800;text-decoration:none">Ingresar a Ruta Docente&nbsp; →</a></td></tr></table>
    </td></tr>
    <tr><td style="padding:13px 26px;background:#fff7e2;border-top:1px solid #f0dfb4;color:#69562c;font-size:11px;line-height:1.5"><strong>Importante:</strong> conserva estos datos en un lugar seguro y no compartas tu contraseña con terceros.</td></tr>
    <tr><td style="padding:17px 26px;background:#031e4c;text-align:center;color:#9eb0c0;font-size:10px;line-height:1.55">Ruta Docente · Un proyecto de Aula Entretenida<br><a href="mailto:aulaentretenida0@gmail.com" style="color:#e6b960;text-decoration:none">aulaentretenida0@gmail.com</a> · +56 9 7577 8434</td></tr>
   </table>
  </td></tr>
 </table>
</body>
</html>

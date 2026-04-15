<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Assignment: {{ $task->task_name }}</title>
</head>
<body style="margin:0; padding:0; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif; color: #2D3748; background-color: #F7FAFC; -webkit-text-size-adjust:100%; -ms-text-size-adjust:100%;">
    <center>
        <table width="100%" cellpadding="0" cellspacing="0" border="0" style="max-width: 600px; margin: 0 auto; background-color: #ffffff;">
            <tr>
                <td style="background: #2D3748; padding: 40px 30px; text-align: center;">
                    <table width="100%" cellpadding="0" cellspacing="0" border="0">
                        <tr>
                            <td style="padding-bottom: 20px;">

 <img src="{{ asset('images/ra3.png') }}" alt="" style="width: 50px; height: 50px; position: absolute; margin-left: -5em;">
                          <p style=" font-size: 13px; font-weight: 500; color: #fff; margin-left: 1.5em; margin-top: 2em;">Raiyaan Infotech</p>

                            </td>
                        </tr>
                        <tr>
                            <td style="padding-bottom: 15px;">
                                <h1 style="font-size: 28px; font-weight: 700; margin: 0; color: #FFFFFF; line-height: 1.3;">{{ $task->task_name }}</h1>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span style="display: inline-block; background: #292828; color: #FFFFFF; padding: 6px 16px; border-radius: 20px; font-size: 13px; font-weight: 500;">
                                    {{ $task->task_accessmod == 0 ? '🌐 Public Task' : '🔒 Private Task' }}
                                </span>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

            <tr>
                <td style="padding: 40px 30px;">

<table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom: 30px;">
    <tr>
        <td>
            <p style="color: #4A5568; line-height: 1.6; font-size: 16px; margin: 0 0 20px 0;">
                <strong>Hello {{ $recipientName }},</strong>
            </p>
            <p style="color: #718096; line-height: 1.6; font-size: 15px; margin: 0;">
                @if($isEdit)
                Task <strong>{{ $task->task_name }}</strong> has been updated in module <strong>{{ $task->modulo->mod_name }}</strong> by {{ $senderName }}. Please review the updated task details below.
                @else
                You have been assigned to the task <strong>{{ $task->task_name }}</strong> in module <strong>{{ $task->modulo->mod_name }}</strong> by {{ $senderName }}. Please review the task details below.
                @endif
            </p>
        </td>
    </tr>
</table>

                    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background: #F8FAFC; border-radius: 12px; border: 1px solid #E2E8F0; margin-bottom: 30px;">
                        <tr>
                            <td style="padding: 25px;">
                                <h2 style="font-size: 18px; font-weight: 600; color: #2D3748; margin: 0 0 20px 0; padding-bottom: 15px; border-bottom: 1px solid #E2E8F0;">
                                    📋 Task Overview
                                </h2>

                                <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                    <tr>
                                        <td width="120" style="padding: 8px 0; vertical-align: top;">
                                            <strong style="color: #718096; font-size: 14px;">Name:</strong>
                                        </td>
                                        <td style="padding: 8px 0; color: #2D3748; font-size: 15px;">
                                            {{ $task->task_name }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="120" style="padding: 8px 0; vertical-align: top;">
                                            <strong style="color: #718096; font-size: 14px;">Description:</strong>
                                        </td>
                                        <td style="padding: 8px 0; color: #2D3748; font-size: 15px;">
                                            {{ $task->task_desc ?? 'No description provided' }}
                                        </td>
                                    </tr>
                                   <tr>
    <td width="120" style="padding: 8px 0; vertical-align: top;">
        <strong style="color: #718096; font-size: 14px;">Priority:</strong>
    </td>
    <td style="padding: 8px 0; color: #2D3748; font-size: 15px;">
        @php
            $priorityText = match ((int)$task->task_priority) {
                1 => 'Low',
                2 => 'Medium',
                3 => 'High',
                default => 'Unknown',
            };

            $priorityColor = match ((int)$task->task_priority) {
                1 => '#48BB78',
                2 => '#ED8936',
                3 => '#F56565',
                default => '#718096',
            };
        @endphp
        <span style="background: {{ $priorityColor }}; color: white; padding: 4px 12px; border-radius: 12px; font-size: 12px; font-weight: 500;">{{ $priorityText }}</span>
    </td>
</tr>
                                </table>

                                <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-top: 20px;">
                                    <tr>
                                        <td>
                                            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                                <tr>
                                                    <td width="50%" style="padding-right: 10px;">
                                                        <div style="background: white; border: 1px solid #E2E8F0; border-radius: 8px; padding: 15px;">
                                                            <div style="color: #718096; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 5px;">Start Date</div>
                                                            <div style="color: #2D3748; font-size: 16px; font-weight: 600;">{{ \Carbon\Carbon::parse($task->created_at)->format('d M, Y') }}</div>
                                                        </div>
                                                    </td>
                                                    <td width="50%" style="padding-left: 10px;">
                                                        <div style="background: white; border: 1px solid #E2E8F0; border-radius: 8px; padding: 15px;">
                                                            <div style="color: #718096; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 5px;">Deadline</div>
                                                            <div style="color: #2D3748; font-size: 16px; font-weight: 600;">
                                                                {{ $task->task_deadline ? \Carbon\Carbon::parse($task->task_deadline)->format('d M, Y') : 'TBD' }}
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>

@if($task->modulo && $task->modulo->project)
<table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom: 30px;">
    <tr>
        <td>
            <h2 style="font-size: 18px; font-weight: 600; color: #2D3748; margin: 0 0 20px 0;">🏢 Project & Module Details</h2>

            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background: #F8FAFC; border-radius: 12px; border: 1px solid #E2E8F0;">
                <tr>
                    <td style="padding: 20px;">
                        <table width="100%" cellpadding="0" cellspacing="0" border="0">
                            <tr>
                                <td style="padding-bottom: 15px; border-bottom: 1px solid #E2E8F0;">
                                    <h3 style="font-size: 16px; font-weight: 600; color: #2D3748; margin: 0 0 10px 0;">Project</h3>
                                    <div style="display: flex; align-items: flex-start;">
                                        <div style="flex-shrink: 0; margin-right: 15px;">
                                            @if($task->modulo->project->pro_avater && file_exists(public_path($task->modulo->project->pro_avater)))
                                            <img src="{{ asset($task->modulo->project->pro_avater) }}" style="width: 40px; height: 40px; border-radius: 8px; object-fit: cover;" alt="{{ $task->modulo->project->pro_name }}">
                                            @else
                                            <div style="width: 40px; height: 40px; border-radius: 8px; background: #4299E1; color: white; display: flex; align-items: center; justify-content: center; font-weight: 600; font-size: 16px;">
                                                {{ substr($task->modulo->project->pro_name, 0, 1) }}
                                            </div>
                                            @endif
                                        </div>
                                        <div style="flex: 1;">
                                            <div style="font-weight: 600; color: #2D3748; font-size: 15px;">{{ $task->modulo->project->pro_name }}</div>
                                            <div style="color: #718096; font-size: 14px; margin-top: 5px; line-height: 1.4;">{{ $task->modulo->project->pro_desc ?? 'No description' }}</div>
                                            <div style="color: #2D3748; font-size: 13px; margin-top: 8px; font-weight: 500;">
                                                Deadline: {{ $task->modulo->project->pro_deadline ? \Carbon\Carbon::parse($task->modulo->project->pro_deadline)->format('d M, Y') : 'Not set' }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding-top: 15px;">
                                    <h3 style="font-size: 16px; font-weight: 600; color: #2D3748; margin: 0 0 10px 0;">Module</h3>
                                    <div style="display: flex; align-items: flex-start;">
                                        <div style="flex-shrink: 0; margin-right: 15px;">
                                            @if($task->modulo->mod_avater && file_exists(public_path($task->modulo->mod_avater)))
                                            <img src="{{ asset($task->modulo->mod_avater) }}" style="width: 40px; height: 40px; border-radius: 8px; object-fit: cover;" alt="{{ $task->modulo->mod_name }}">
                                            @else
                                            <div style="width: 40px; height: 40px; border-radius: 8px; background: #48BB78; color: white; display: flex; align-items: center; justify-content: center; font-weight: 600; font-size: 16px;">
                                                {{ substr($task->modulo->mod_name, 0, 1) }}
                                            </div>
                                            @endif
                                        </div>
                                        <div style="flex: 1;">
                                            <div style="font-weight: 600; color: #2D3748; font-size: 15px;">{{ $task->modulo->mod_name }}</div>
                                            <div style="color: #718096; font-size: 14px; margin-top: 5px; line-height: 1.4;">{{ $task->modulo->mod_desc ?? 'No description' }}</div>
                                            <div style="color: #2D3748; font-size: 13px; margin-top: 8px; font-weight: 500;">
                                                Deadline: {{ $task->modulo->mod_deadline ? \Carbon\Carbon::parse($task->modulo->mod_deadline)->format('d M, Y') : 'Not set' }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
@endif

@if($task->modulo && $task->modulo->project)
<table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom: 30px;">
    <tr>
        <td>
            <h2 style="font-size: 18px; font-weight: 600; color: #2D3748; margin: 0 0 20px 0;">👥 Project Leadership</h2>

            @if($task->modulo->project->projectHead || $task->modulo->project->projectLead)
            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom: 20px;">
                @if($task->modulo->project->projectHead)
                <tr>
                    <td style="padding-bottom: 15px;">
                        <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background: #F8FAFC; border-radius: 8px; padding: 15px; border: 1px solid #E2E8F0;">
                            <tr>
                                <td width="40" style="vertical-align: top; padding-right: 15px;">
                                    @if($task->modulo->project->projectHead->image)
                                    <img src="{{ asset('employee_images/' . $task->modulo->project->projectHead->image) }}" style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover;" alt="{{ $task->modulo->project->projectHead->fullname }}">
                                    @else
                                    <div style="width: 40px; height: 40px; border-radius: 50%; background: #4299E1; color: white; display: flex; align-items: center; justify-content: center; font-weight: 600; font-size: 16px;">
                                        {{ substr($task->modulo->project->projectHead->fullname, 0, 1) }}
                                    </div>
                                    @endif
                                </td>
                                <td>
                                    <div style="font-weight: 600; color: #2D3748; font-size: 15px;">{{ $task->modulo->project->projectHead->fullname }}</div>
                                    <div style="color: #718096; font-size: 13px; margin-top: 2px;">
                                        {{ $task->modulo->project->projectHead->designationid->des_name ?? 'N/A' }}
                                    </div>
                                    <div style="color: #4299E1; font-size: 12px; margin-top: 2px;">Project Manager</div>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                @endif

                @if($task->modulo->project->projectLead)
                <tr>
                    <td style="padding-bottom: 15px;">
                        <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background: #F8FAFC; border-radius: 8px; padding: 15px; border: 1px solid #E2E8F0;">
                            <tr>
                                <td width="40" style="vertical-align: top; padding-right: 15px;">
                                    @if($task->modulo->project->projectLead->image)
                                    <img src="{{ asset('employee_images/' . $task->modulo->project->projectLead->image) }}" style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover;" alt="{{ $task->modulo->project->projectLead->fullname }}">
                                    @else
                                    <div style="width: 40px; height: 40px; border-radius: 50%; background: #4299E1; color: white; display: flex; align-items: center; justify-content: center; font-weight: 600; font-size: 16px;">
                                        {{ substr($task->modulo->project->projectLead->fullname, 0, 1) }}
                                    </div>
                                    @endif
                                </td>
                                <td>
                                    <div style="font-weight: 600; color: #2D3748; font-size: 15px;">{{ $task->modulo->project->projectLead->fullname }}</div>
                                    <div style="color: #718096; font-size: 13px; margin-top: 2px;">
                                        {{ $task->modulo->project->projectLead->designationid->des_name ?? 'N/A' }}
                                    </div>
                                    <div style="color: #4299E1; font-size: 12px; margin-top: 2px;">Project Lead</div>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                @endif
            </table>
            @endif
        </td>
    </tr>
</table>
@endif

@if($task->modulo && $task->modulo->project && $task->modulo->project->client)
<table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom: 30px;">
    <tr>
        <td>
            <h2 style="font-size: 18px; font-weight: 600; color: #2D3748; margin: 0 0 20px 0;">🤝 Client</h2>
            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background: #F8FAFC; border-radius: 8px; padding: 15px; border: 1px solid #E2E8F0;">
                <tr>
                    <td width="40" style="vertical-align: top; padding-right: 15px;">
                        @if($task->modulo->project->client->cl_image && file_exists(public_path('client_images/' . $task->modulo->project->client->cl_image)))
                        <img src="{{ asset('client_images/' . $task->modulo->project->client->cl_image) }}" style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover;" alt="{{ $task->modulo->project->client->cl_name }}">
                        @else
                        <div style="width: 40px; height: 40px; border-radius: 50%; background: #ED8936; color: white; display: flex; align-items: center; justify-content: center; font-weight: 600; font-size: 16px;">
                            {{ substr($task->modulo->project->client->cl_name, 0, 1) }}
                        </div>
                        @endif
                    </td>
                    <td>
                        <div style="font-weight: 600; color: #2D3748; font-size: 15px;">{{ $task->modulo->project->client->cl_name }}</div>
                        <div style="color: #718096; font-size: 13px; margin-top: 2px;">{{ $task->modulo->project->client->cl_email }}</div>
                        @if($task->modulo->project->client->cl_phone)
                        <div style="color: #718096; font-size: 13px; margin-top: 2px;">{{ $task->modulo->project->client->cl_phone }}</div>
                        @endif
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
@endif

                    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom: 30px;">
                        <tr>
                            <td>
                                <h2 style="font-size: 18px; font-weight: 600; color: #2D3748; margin: 0 0 20px 0;">👤 Assigned To</h2>

                                @if($task->assignedEmployee)
                                <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background: #F8FAFC; border-radius: 8px; padding: 15px; border: 1px solid #E2E8F0;">
                                    <tr>
                                        <td width="40" style="vertical-align: top; padding-right: 15px;">
                                            @if($task->assignedEmployee->image && file_exists(public_path('employee_images/' . $task->assignedEmployee->image)))
                                            <img src="{{ asset('employee_images/' . $task->assignedEmployee->image) }}" style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover;" alt="{{ $task->assignedEmployee->fullname }}">
                                            @else
                                            <div style="width: 40px; height: 40px; border-radius: 50%; background: #48BB78; color: white; display: flex; align-items: center; justify-content: center; font-weight: 600; font-size: 16px;">
                                                {{ substr($task->assignedEmployee->fullname, 0, 1) }}
                                            </div>
                                            @endif
                                        </td>
                                        <td>
                                            <div style="font-weight: 600; color: #2D3748; font-size: 15px;">{{ $task->assignedEmployee->fullname }}</div>
                                            <div style="color: #718096; font-size: 13px; margin-top: 2px;">
                                                {{ $task->assignedEmployee->designationid->des_name ?? 'Team Member' }}
                                            </div>
                                            @if($task->assignedEmployee->email_company)
                                            <div style="color: #4299E1; font-size: 12px; margin-top: 2px;">{{ $task->assignedEmployee->email_company }}</div>
                                            @endif
                                        </td>
                                    </tr>
                                </table>
                                @else
                                <div style="text-align: center; padding: 20px; color: #718096; font-style: italic; background: #F8FAFC; border-radius: 8px; border: 1px dashed #E2E8F0;">
                                    No assignee selected.
                                </div>
                                @endif
                            </td>
                        </tr>
                    </table>

                    @if($task->pmtsImages && $task->pmtsImages->count() > 0)
                    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom: 30px;">
                        <tr>
                            <td>
                                <h2 style="font-size: 18px; font-weight: 600; color: #2D3748; margin: 0 0 20px 0;">📎 Task Attachments</h2>
                                <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background: #F8FAFC; border-radius: 8px; padding: 20px; border: 1px solid #E2E8F0;">
                                    <tr>
                                        <td>
                                            <div style="color: #718096; font-size: 14px; margin-bottom: 15px;">
                                                <strong>{{ $task->pmtsImages->count() }}</strong> file(s) attached
                                            </div>

                                            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                                @foreach($task->pmtsImages as $attachment)
                                                <tr>
                                                    <td style="padding: 10px 0; border-bottom: 1px solid #E2E8F0;">
                                                        <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                                            <tr>
                                                                <td width="40" style="vertical-align: top; padding-right: 15px;">
                                                                    @php
                                                                        $extension = strtolower(pathinfo($attachment->pmtsimage_name, PATHINFO_EXTENSION));
                                                                        $isImage = in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp']);
                                                                        $isPDF = $extension === 'pdf';
                                                                        $isDoc = in_array($extension, ['doc', 'docx']);
                                                                        $isExcel = in_array($extension, ['xls', 'xlsx', 'csv']);
                                                                    @endphp

                                                                    <div style="width: 40px; height: 40px; border-radius: 8px; background: #4299E1; display: flex; align-items: center; justify-content: center; color: white; font-size: 18px;">
                                                                        @if($isImage)
                                                                        📷
                                                                        @elseif($isPDF)
                                                                        📄
                                                                        @elseif($isDoc)
                                                                        📝
                                                                        @elseif($isExcel)
                                                                        📊
                                                                        @else
                                                                        📎
                                                                        @endif
                                                                    </div>
                                                                </td>
                                                                <td style="vertical-align: middle;">
                                                                    <div style="font-weight: 500; color: #2D3748; font-size: 14px;">
                                                                        {{ $attachment->pmtsimage_name }}
                                                                    </div>
                                                                    <div style="color: #718096; font-size: 12px; margin-top: 2px;">
                                                                        {{ strtoupper($extension) }} •
                                                                        {{ $attachment->created_at ? \Carbon\Carbon::parse($attachment->created_at)->format('M d, Y') : '' }}
                                                                    </div>
                                                                </td>
                                                                <td width="120" style="vertical-align: middle; text-align: right;">
                                                                    @if($isImage && file_exists(public_path('task_attachments/' . $attachment->pmtsimage_name)))
                                                                    <a href="{{ asset('task_attachments/' . $attachment->pmtsimage_name) }}" download style="color: #48BB78; text-decoration: none; font-size: 13px; font-weight: 500;">Download</a>
                                                                    @else
                                                                    <a href="{{ asset('task_attachments/' . $attachment->pmtsimage_name) }}" download style="color: #48BB78; text-decoration: none; font-size: 13px; font-weight: 500;">Download</a>
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </table>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                    @endif

                    <table width="100%" cellpadding="0" cellspacing="0" border="0">
                        <tr>
                            <td style="text-align: center; padding: 30px 0;">
                                <a href="{{ url('http://127.0.0.1:8000/dashboard/employees/empkanbanboard/') }}" style="display: inline-block; background: #4299E1; color: #FFFFFF; padding: 14px 32px; border-radius: 8px; text-decoration: none; font-weight: 600; font-size: 16px; transition: all 0.3s;">
                                    📊 Access Task Dashboard
                                </a>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

            <tr>
                <td style="background: #2D3748; color: #CBD5E0; padding: 30px; text-align: center;">
                    <table width="100%" cellpadding="0" cellspacing="0" border="0">
                        <tr>
                            <td style="padding-bottom: 20px;">
                                <a href="https://raiyaaninfotech.com" style="color: #90CDF4; text-decoration: none; margin: 0 10px; font-size: 14px;">Website</a>
                                <a href="mailto:support@raiyaaninfotech.com" style="color: #90CDF4; text-decoration: none; margin: 0 10px; font-size: 14px;">Support</a>
                                <a href="#" style="color: #90CDF4; text-decoration: none; margin: 0 10px; font-size: 14px;">Contact</a>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div style="color: #A0AEC0; font-size: 13px; line-height: 1.5;">
                                    © 2024 Raiyaaninfotech. All rights reserved.<br>
                                    <span style="font-size: 12px; color: #718096;">
                                        This is an automated notification email.
                                    </span>
                                </div>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </center>
</body>
</html>

<x-layout>
    <div class="container-fluid px-4 py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h5 class="mb-0 mt-2 fw-bold text-dark">Task Conversation</h5>
                <p class="text-muted mb-0">Chat about your tasks and subtasks</p>
            </div>
        </div>

        <div class="row chat-container g-3">
            <div class="col-lg-3 user-info-section" id="userInfoSection">
                <div class="toggle-icon2" id="toggleIcon" onclick="toggleUserInfoSection()">
                    <i class="bi bi-shuffle"></i>
                </div>

                <div class="user-info-content h-100 glass-card">
                    <div class="section-header text-center">
                        <h6 class="mb-0 fw-bold text-gray-800">My Statistics</h6>
                    </div>

                    <div class="px-4">
                        <div class="mb-4 mt-3">
                            <div class="text-center">
                                @if($statistics['isProjectLead'])
                                    <span class="badge bg-success text-light p-2" style="font-size: 13px;">
                                        <i class="bi bi-star-fill me-1"></i> Project Lead
                                    </span>
                                    <p class="text-muted mt-1 small">Viewing all tasks & subtasks from your projects</p>
                                @else
                                    <span class="badge bg-primary text-light p-2" style="font-size: 11.5px;">
                                        <i class="bi bi-person-fill me-1"></i> Team Member
                                    </span>
                                    <p class="text-muted mt-1 small">Viewing only your assigned tasks & subtasks</p>
                                @endif
                            </div>
                        </div>

                        <div class="assignee-section" id="dynamicAssignee">
                            <div class="text-center mb-4">
                                <div class="no-assignee">
                                    <i class="bi bi-person-badge display-6 text-gray-300 mb-3"></i>
                                </div>
                            </div>
                        </div>

                        <div class="">
                            <div class="stats-list">
                                <span class="badge bg-task text-light p-2 mb-2" style="font-size: 11.5px;">Task</span>
                                <div class="stat-item">
                                    <span class="text-gray-700 stat-head">Total Tasks</span>
                                    <span class="stat-value" id="statTotalTasks">{{ $statistics['totalTasks'] }}</span>
                                </div>

                                <div class="stat-item">
                                    <span class="text-gray-700 stat-head">On Time</span>
                                    <span class="stat-value" id="statTasksOnTime">{{ $statistics['tasksOnTime'] }}</span>
                                </div>

                                <div class="stat-item">
                                    <span class="text-gray-700 stat-head">On Progress</span>
                                    <span class="stat-value" id="statTasksOnProgress">{{ $statistics['tasksOnProgress'] }}</span>
                                </div>

                                <div class="stat-item">
                                    <span class="text-gray-700 stat-head">Overdue</span>
                                    <span class="stat-value" id="statTasksOverdue">{{ $statistics['tasksOverdue'] }}</span>
                                </div>
                            </div>

                            <div class="stats-list mt-2">
                                <span class="badge bg-subtask text-light p-2 mb-2" style="font-size: 11.5px;">SubTask</span>
                                <div class="stat-item">
                                    <span class="text-gray-700 stat-head">Total Subtasks</span>
                                    <span class="stat-value" id="statTotalSubtasks">{{ $statistics['totalSubtasks'] }}</span>
                                </div>
                                <div class="stat-item">
                                    <span class="text-gray-700 stat-head">On Time</span>
                                    <span class="stat-value" id="statSubtasksOnTime">{{ $statistics['subtasksOnTime'] }}</span>
                                </div>
                                <div class="stat-item">
                                    <span class="text-gray-700 stat-head">On Progress</span>
                                    <span class="stat-value" id="statSubtasksOnProgress">{{ $statistics['subtasksOnProgress'] }}</span>
                                </div>
                                <div class="stat-item">
                                    <span class="text-gray-700 stat-head">Overdue</span>
                                    <span class="stat-value" id="statSubtasksOverdue">{{ $statistics['subtasksOverdue'] }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3">
                <div class="task-list-section h-100 glass-card">
                    <div class="section-header">
                        <div class="search-container">
                            <i class="bi bi-search search-icon"></i>
                            <input type="text" class="search-input form-control border-0 w-100 outline-none text-sm" placeholder="Search....." id="taskSearch">
                        </div>
                    </div>

                    <div class="task-scroll">
                        @foreach($tasks as $task)
                            @php
                                $taskProLead = $task->modulo->project->projectLead ?? null;
                                $taskProLeadId = $taskProLead ? $taskProLead->emp_id : null;
                                $taskProLeadName = $taskProLead ? $taskProLead->fullname : 'Unassigned';
                                $taskProLeadImage = $taskProLead ? $taskProLead->image : null;
                                $taskProLeadDesignation = $taskProLead ? ($taskProLead->designationid->des_name ?? 'No designation') : 'No designation';
                                $taskProLeadEmail = $taskProLead ? ($taskProLead->email_company ?? 'No email') : 'No email';

                                if ($statistics['isProjectLead']) {
                                    $firstAssignedId = $controller->extractFirstId($task->task_assignedto);
                                    if ($firstAssignedId) {
                                        $assignedEmployee = \App\Models\Employee::find($firstAssignedId);
                                        $chatWithName = $assignedEmployee ? $assignedEmployee->fullname : 'Unassigned';
                                        $chatWithImage = $assignedEmployee ? $assignedEmployee->image : null;
                                        $chatWithDesignation = $assignedEmployee ? ($assignedEmployee->designationid->des_name ?? 'No designation') : 'No designation';
                                        $chatWithEmail = $assignedEmployee ? ($assignedEmployee->email_company ?? 'No email') : 'No email';
                                    } else {
                                        $chatWithName = 'Unassigned';
                                        $chatWithImage = null;
                                        $chatWithDesignation = 'No designation';
                                        $chatWithEmail = 'No email';
                                    }
                                } else {
                                    $chatWithName = $taskProLeadName;
                                    $chatWithImage = $taskProLeadImage;
                                    $chatWithDesignation = $taskProLeadDesignation;
                                    $chatWithEmail = $taskProLeadEmail;
                                }
                            @endphp

                            <div class="task-item p-3 glass-card" id="task-{{ $task->task_id }}">
                                <div class="d-flex justify-content-between align-items-center cursor-pointer" onclick="toggleSubtasks({{ $task->task_id }})">
                                    <div class="d-flex align-items-center">
                                        <div class="icon-icon-cl">
                                            <span class="circle-icon">
                                                <i class="bi bi-circle text-primary small" style="font-size: 20px;"></i>
                                            </span>
                                            <span class="list-icon">
                                                <i class="bi bi-list-task text-primary" style="font-size: 12px;"></i>
                                            </span>
                                        </div>
                                        <div class="task-notification-icon me-2" id="task-notification-{{ $task->task_id }}">
                                            <span class="task-notification-badge" id="task-bell-{{ $task->task_id }}"></span>
                                        </div>
                                        <span class="fw-semibold text-gray-900 task-name ms-3" style="font-size: 14px;" data-task-id="{{ $task->task_id }}" data-task-name="{{ $task->task_name }}" data-chatwith-name="{{ $chatWithName }}" data-chatwith-image="{{ $chatWithImage }}" data-chatwith-designation="{{ $chatWithDesignation }}" data-chatwith-email="{{ $chatWithEmail }}" onclick="loadTaskChat({{ $task->task_id }}, '{{ addslashes($task->task_name) }}', '{{ addslashes($chatWithName) }}', '{{ $chatWithImage }}', '{{ addslashes($chatWithDesignation) }}', '{{ addslashes($chatWithEmail) }}')">
                                            {{ Str::limit($task->task_name, 30) }}
                                        </span>
                                    </div>
                                    <i class="bi bi-chevron-down text-primary small" id="icon-{{ $task->task_id }}"></i>
                                </div>

                                <div class="subtask-list mt-2" id="subtasks-{{ $task->task_id }}" style="display: none;">
                                    @foreach($task->subtasks as $subtaskItem)
                                        @php
                                            $subtaskProLead = $subtaskItem->task->modulo->project->projectLead ?? null;
                                            $subtaskProLeadId = $subtaskProLead ? $subtaskProLead->emp_id : null;
                                            $subtaskProLeadName = $subtaskProLead ? $subtaskProLead->fullname : 'Unassigned';
                                            $subtaskProLeadImage = $subtaskProLead ? $subtaskProLead->image : null;
                                            $subtaskProLeadDesignation = $subtaskProLead ? ($subtaskProLead->designationid->des_name ?? 'No designation') : 'No designation';
                                            $subtaskProLeadEmail = $subtaskProLead ? ($subtaskProLead->email_company ?? 'No email') : 'No email';

                                            if ($statistics['isProjectLead']) {
                                                $firstAssignedId = $controller->extractFirstId($subtaskItem->stask_assignedto);
                                                if ($firstAssignedId) {
                                                    $assignedEmployee = \App\Models\Employee::find($firstAssignedId);
                                                    $subtaskChatWithName = $assignedEmployee ? $assignedEmployee->fullname : 'Unassigned';
                                                    $subtaskChatWithImage = $assignedEmployee ? $assignedEmployee->image : null;
                                                    $subtaskChatWithDesignation = $assignedEmployee ? ($assignedEmployee->designationid->des_name ?? 'No designation') : 'No designation';
                                                    $subtaskChatWithEmail = $assignedEmployee ? ($assignedEmployee->email_company ?? 'No email') : 'No email';
                                                } else {
                                                    $subtaskChatWithName = 'Unassigned';
                                                    $subtaskChatWithImage = null;
                                                    $subtaskChatWithDesignation = 'No designation';
                                                    $subtaskChatWithEmail = 'No email';
                                                }
                                            } else {
                                                $subtaskChatWithName = $subtaskProLeadName;
                                                $subtaskChatWithImage = $subtaskProLeadImage;
                                                $subtaskChatWithDesignation = $subtaskProLeadDesignation;
                                                $subtaskChatWithEmail = $subtaskProLeadEmail;
                                            }
                                        @endphp

                                        <div class="subtask-item p-2" id="subtask-{{ $subtaskItem->stask_id }}">
                                            <div class="d-flex align-items-center">
                                                <div class="icon-icon-cl">
                                                    <span class="circle-icon">
                                                        <i class="bi bi-circle text-primary small" style="font-size: 20px;"></i>
                                                    </span>
                                                    <span class="list-icon">
                                                        <i class="bi bi-list-nested text-primary" style="font-size: 12px;"></i>
                                                    </span>
                                                </div>
                                                <div class="task-notification-icon me-2" id="subtask-notification-{{ $subtaskItem->stask_id }}">
                                                    <span class="task-notification-badge" id="subtask-bell-{{ $subtaskItem->stask_id }}"></span>
                                                </div>
                                                <a href="javascript:void(0)" class="text-decoration-none text-sm text-gray-700 subtask-name ms-3" data-subtask-id="{{ $subtaskItem->stask_id }}" data-subtask-name="{{ $subtaskItem->stask_name }}" data-chatwith-name="{{ $subtaskChatWithName }}" data-chatwith-image="{{ $subtaskChatWithImage }}" data-chatwith-designation="{{ $subtaskChatWithDesignation }}" data-chatwith-email="{{ $subtaskChatWithEmail }}" onclick="loadSubtaskChat({{ $subtaskItem->stask_id }}, '{{ addslashes($subtaskItem->stask_name) }}', '{{ addslashes($subtaskChatWithName) }}', '{{ $subtaskChatWithImage }}', '{{ addslashes($subtaskChatWithDesignation) }}', '{{ addslashes($subtaskChatWithEmail) }}')">
                                                    {{ Str::limit($subtaskItem->stask_name, 26) }}
                                                </a>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="col-lg-6 chat-col" id="chatCol">
                <div class="chat-section h-100 glass-card">
                    <div class="section-header d-flex justify-content-between align-items-center" id="chatSectionHeader" style="display: none;">
                        <h5 class="mb-0 fw-bold text-gray-800" id="currentChatTitle"></h5>
                        <div class="" id="currentAssignee">
                            <div class="d-flex align-items-center" style="border-radius: 0px;" id="assigneeInfo"></div>
                        </div>
                    </div>

                    <div class="chat-messages" id="chatMessages">
                        <div class="text-center align-content-center h-100 text-gray-500">
                            <i class="bi bi-chat-square-dots display-4 text-gray-300"></i>
                            <p class="text-lg text-gray-600 mt-3">Welcome to Task Conversation</p>
                            <p class="text-sm text-gray-500">Select a task or subtask from the left panel to start conversation</p>
                        </div>
                    </div>

                    <div class="chat-input-container" style="display: none;" id="chatInputContainer">
                        <div id="replyIndicator" class="reply-indicator mb-3" style="display: none;">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <small class="fw-bold text-primary">Replying to:</small>
                                    <span id="replyMessageText" class="text-sm text-gray-700"></span>
                                </div>
                                <button type="button" class="btn-close btn-sm" onclick="cancelReply()"></button>
                            </div>
                        </div>

                            <div class="d-flex align-items-center gap-2 mb-3 rounded-3">
                            <button type="button" class="toolbar-btn" onclick="formatText('bold')" title="Bold">
                                <i class="bi bi-type-bold"></i>
                            </button>
                            <button type="button" class="toolbar-btn" onclick="formatText('italic')" title="Italic">
                                <i class="bi bi-type-italic"></i>
                            </button>
                            <button type="button" class="toolbar-btn" onclick="formatText('underline')" title="Underline">
                                <i class="bi bi-type-underline"></i>
                            </button>
                            <button type="button" class="toolbar-btn" onclick="formatText('strikeThrough')" title="Strikethrough">
                                <i class="bi bi-type-strikethrough"></i>
                            </button>
                            <button type="button" class="toolbar-btn" onclick="insertLink()" title="Insert Link">
                                <i class="bi bi-link"></i>
                            </button>
                            <button type="button" class="toolbar-btn" onclick="formatText('insertUnorderedList')" title="Bullet List">
                                <i class="bi bi-list-ul"></i>
                            </button>
                            <button type="button" class="toolbar-btn" onclick="formatText('insertOrderedList')" title="Numbered List">
                                <i class="bi bi-list-ol"></i>
                            </button>
                        </div>

                        <div class="input-toolbar mb-3">
                            <div id="messageEditor" class="w-100 border-0 bg-transparent outline-none resize-none" contenteditable="true" placeholder="Type your message here..." style="min-height: 50px; max-height: 100px; overflow-y: auto; padding: 8px;"></div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center gap-3">
                                <button class="toolbar-btn" title="Attach files" onclick="document.getElementById('fileInput').click()">
                                    <i class="bi bi-paperclip"></i>
                                </button>
                                <span class="text-sm text-gray-600" id="fileInfo">No files selected</span>
                                <input type="file" id="fileInput" multiple style="display: none;" onchange="handleFileSelection(this.files)">
                            </div>

                            <div class="d-flex align-items-center gap-2">
                                <button class="btn btn-outline-secondary" id="cancelEditBtn" style="display: none;" onclick="cancelEditMessage()">
                                    Cancel
                                </button>
                                <button class="send-btn" onclick="sendMessage()" id="sendMessageBtn">
                                    <i class="bi bi-send me-2"></i> Send
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="imageModal" class="image-modal">
        <span class="close-modal" onclick="closeImageModal()">&times;</span>
        <div class="modal-content">
            <div class="zoom-info" id="zoomInfo">100%</div>
            <div class="prev-arrow side-arrow" onclick="prevImage()" id="prevArrow">
                <i class="bi bi-chevron-left"></i>
            </div>
            <img id="modalImage" class="modal-image" src="" alt="Full size image">
            <div class="next-arrow side-arrow" onclick="nextImage()" id="nextArrow">
                <i class="bi bi-chevron-right"></i>
            </div>
            <div class="modal-controls">
                <button class="modal-btn" onclick="zoomOut()" title="Zoom Out">
                    <i class="bi bi-dash-lg"></i>
                </button>
                <button class="modal-btn" onclick="resetZoom()" title="Reset Zoom">
                    <i class="bi bi-arrow-repeat"></i>
                </button>
                <button class="modal-btn" onclick="zoomIn()" title="Zoom In">
                    <i class="bi bi-plus-lg"></i>
                </button>
                <button class="modal-btn" onclick="downloadImage()" title="Download">
                    <i class="bi bi-download"></i>
                </button>
            </div>
        </div>
    </div>

    <style>
        .toolbar-btn.active {
            background: var(--ra-primary) !important;
            color: white !important;
            border-color: var(--ra-primary) !important;
        }
        .toolbar-btn.active:hover {
            background: #5a6fd8 !important;
            color: white !important;
            border-color: #5a6fd8 !important;
        }
        .chat-container { height: 85vh; font-family: 'Inter', system-ui, sans-serif; }
        .glass-card { background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.2); border-radius: 16px; box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1); position: relative; }
        .user-info-section { height: 100%; padding: 0; transition: all 0.3s ease; position: relative; }
        .user-info-collapsed { flex: 0 0 60px !important; max-width: 60px !important; overflow: hidden; }
        .col-expand-1 { flex: 0 0 70.834% !important; max-width: 70.834% !important; }
        .user-avatar { width: 50px; height: 50px; border-radius: 50%; object-fit: cover; border: 4px solid rgba(255, 255, 255, 0.8); box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15); }
        .user-avatar-small { width: 40px; height: 40px; border-radius: 50%; object-fit: cover; border: 2px solid rgba(255, 255, 255, 0.8); }
        .stat-card { background: white; border-radius: 12px; padding: 15px; border-left: 4px solid var(--ra-primary); box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08); margin-bottom: 12px; transition: all 0.3s ease; }
        .stat-card:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12); }
        .stat-item { display: flex; justify-content: space-between; align-items: center; padding: 8px 0; border-bottom: 1px solid rgba(0, 0, 0, 0.05); }
        .stat-item:last-child { border-bottom: none; }
        .stat-value { font-weight: bold; color: var(--ra-primary); font-size: 13px; }
        .stat-head{ font-size: 13px; }
        #statTasksOnTime, #statSubtasksOnTime { color: #04a80c; }
        #statTasksOnProgress, #statSubtasksOnProgress { color: #a8a504; }
        #statTasksOverdue, #statSubtasksOverdue { color: #a80404; }
        .task-list-section { height: 100%; padding: 0; }
        .task-item { border-bottom: 1px solid rgba(0, 0, 0, 0.05); transition: all 0.3s ease; border-radius: 8px; margin: 4px 0; position: relative; }
        .task-item:hover { background: rgba(102, 126, 234, 0.1); transform: translateX(4px); }
        .task-item.active { background: linear-gradient(135deg, rgba(102, 126, 234, 0.15), rgba(118, 75, 162, 0.1)); border-left: 4px solid var(--ra-primary); }
        .subtask-list { background: rgba(102, 126, 234, 0.05); border-radius: 8px; margin-left: 28px; }
        .subtask-item.active { background: linear-gradient(135deg, rgba(102, 126, 234, 0.15), rgba(118, 75, 162, 0.1)); border-left: 4px solid var(--ra-primary); }
        .chat-section { height: 100%; display: flex; flex-direction: column; padding: 0; }
        .chat-messages { flex: 1; overflow-y: auto; padding: 20px; background: rgba(151, 152, 153, 0.144); backdrop-filter: blur(5px); }
        .message-bubble { max-width: 75%; margin-bottom: 16px; animation: fadeInUp 0.3s ease; }
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        .message-sent { margin-left: auto; justify-content: flex-end; }
        .message-received { margin-right: auto; justify-content: flex-start; }
        .chat-input-container { border-top: 1px solid rgba(0, 0, 0, 0.08); padding: 16px 20px; background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(10px); border-radius: 0 0 16px 16px; }
        .section-header { padding: 20px; border-bottom: 1px solid rgba(0, 0, 0, 0.08); background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(10px); border-radius: 16px 16px 0 0; position: relative; }
        .input-toolbar { background: rgba(248, 250, 252, 0.9); border: 2px solid rgba(102, 126, 234, 0.15); border-radius: 16px; transition: all 0.3s ease; }
        .input-toolbar:focus-within { border-color: var(--ra-primary); box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1); background: white; }
        .toolbar-btn { width: 36px; height: 36px; border: 2px solid rgba(102, 126, 234, 0.15); border-radius: 10px; display: flex; align-items: center; justify-content: center; background: white; color: var(--ra-primary); transition: all 0.3s ease; font-size: 16px; }
        .toolbar-btn:hover { background: var(--ra-primary); color: white; border-color: var(--ra-primary); transform: scale(1.05); }
        .bg-task, .bg-subtask { background: var(--ra-primary); }
        .send-btn { background: var(--ra-primary); color: white; border: none; border-radius: 25px; padding: 8px 24px; transition: all 0.3s ease; font-weight: 600; box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4); }
        .send-btn:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(102, 126, 234, 0.6); }
        .assignee-section { border-bottom: 1px solid rgba(0, 0, 0, 0.08); padding-bottom: 20px; margin-bottom: 20px; }
        .no-assignee { color: #6b7280; font-style: italic; }
        .search-container { position: relative; }
        .search-icon { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: var(--ra-primary); }
        .search-input { padding-left: 40px; border: 2px solid rgba(102, 126, 234, 0.15); border-radius: 12px; background: rgba(255, 255, 255, 0.9); transition: all 0.3s ease; }
        .search-input:focus { border-color: var(--ra-primary); box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1); background: white; }
        .task-scroll { max-height: 75vh; overflow-y: auto; padding: 8px; }
        .task-scroll::-webkit-scrollbar { width: 6px; }
        .task-scroll::-webkit-scrollbar-track { background: rgba(0, 0, 0, 0.05); border-radius: 3px; }
        .task-scroll::-webkit-scrollbar-thumb { background: var(--ra-primary); border-radius: 3px; }
        .gradient-bg { background: linear-gradient(135deg, var(--ra-primary) 0%, #764ba2 100%); }
        .message-time { font-size: 11px; opacity: 0.7; }
        .modern-message { display: flex; align-items: flex-start; gap: 8px; margin-bottom: 12px; width: 100%; }
        .message-sent-modern { justify-content: flex-end; }
        .message-received-modern { justify-content: flex-start; }
        .message-content { display: flex; flex-direction: column; max-width: 70%; }
        .message-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px; }
        .message-bubble-modern { padding: 10px 14px; border-radius: 18px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08); position: relative; display: inline-block; max-width: 100%; word-wrap: break-word; }
        .message-sent-bubble { background: var(--ra-primary); color: white; border-bottom-right-radius: 6px; text-align: left; }
        .message-received-bubble { background: white; color: #333; border-bottom-left-radius: 6px; border: 1px solid rgba(0, 0, 0, 0.05); text-align: left; }
        .typing-indicator { display: flex; align-items: center; gap: 8px; padding: 12px 18px; background: white; border-radius: 18px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1); max-width: fit-content; margin-bottom: 16px; }
        .typing-dots { display: flex; gap: 4px; }
        .typing-dot { width: 6px; height: 6px; background: var(--ra-primary); border-radius: 50%; animation: typing 1.4s infinite ease-in-out; }
        .typing-dot:nth-child(1) { animation-delay: -0.32s; }
        .typing-dot:nth-child(2) { animation-delay: -0.16s; }
        @keyframes typing { 0%, 80%, 100% { transform: scale(0.8); opacity: 0.5; } 40% { transform: scale(1); opacity: 1; } }
        .message-actions { position: absolute; top: 4px; right: 4px; opacity: 0; transition: opacity 0.2s ease; }
        .message-bubble-modern:hover .message-actions { opacity: 1; }
        .dropdown-toggle { background: none; border: none; color: inherit; padding: 4px; border-radius: 4px; cursor: pointer; }
        .dropdown-menu { min-width: 120px; border-radius: 8px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15); border: 1px solid rgba(0, 0, 0, 0.08); }
        .dropdown-item { padding: 6px 12px; display: flex; align-items: center; gap: 6px; }
        .dropdown-item:hover { background: rgba(102, 126, 234, 0.1); }
        .time-divider { text-align: center; margin: 20px 0; position: relative; }
        .time-divider-text { background: rgba(255, 255, 255, 0.9); padding: 4px 12px; border-radius: 12px; font-size: 11px; color: #464b55; display: inline-block; position: relative; border: 1px solid rgba(0, 0, 0, 0.05); }
        .reply-indicator { background: rgba(102, 126, 234, 0.1); border-left: 3px solid var(--ra-primary); padding: 8px 12px; border-radius: 8px; margin-bottom: 8px; font-size: 12px; }
        .edit-mode { border: 2px solid var(--ra-primary); background: rgba(102, 126, 234, 0.05); }
        .notification-badge { position: absolute; top: -4px; left: 10px; background: #ff4757; color: #ffffff; border-radius: 50%; width: 9px; height: 9px; display: flex; align-items: center; justify-content: center; font-size: 0; font-weight: bold; }
        .file-attachment { background: white; border-radius: 12px; padding: 12px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1); max-width: 300px; margin: 8px 0; }
        .file-attachment img { width: 100%; max-height: 200px; object-fit: contain; border-radius: 8px; cursor: pointer; transition: transform 0.2s ease; }
        .file-attachment img:hover { transform: scale(1.02); }
        .file-preview { display: flex; align-items: center; gap: 12px; padding: 8px; background: #f8f9fa; border-radius: 8px; margin-bottom: 8px; }
        .file-icon { font-size: 24px; color: var(--ra-primary); }
        .file-info { flex: 1; }
        .file-name { font-weight: 600; font-size: 14px; color: #333; margin-bottom: 4px; word-break: break-word; }
        .file-size { font-size: 12px; color: #6c757d; }
        .download-btn { background: var(--ra-primary); color: white; border: none; border-radius: 6px; padding: 6px 12px; font-size: 12px; cursor: pointer; transition: all 0.2s ease; }
        .download-btn:hover { background: #5a6fd8; }
        .file-attachment:hover .compact-actions { opacity: 1; }
        .task-notification-icon { position: relative; display: none; margin-top: 1.2em; }
        .icon-icon-cl { position: relative; }
        .list-icon { position: absolute; left: 4px; top: 4px; }
        .task-notification-badge { position: absolute; top: -8px; right: -5px; background: #ff4757; color: #857475; border-radius: 50%; width: 9px; height: 9px; display: flex; align-items: center; justify-content: center; font-size: 0; font-weight: bold; }
        .notification-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 15px; }
        .notification-icon { position: relative; font-size: 20px; color: var(--ra-primary); }
        .stats-list { background: white; border-radius: 12px; padding: 15px; }
        .edit-container { background: rgba(255, 255, 255, 0.95); border-radius: 12px; padding: 12px; margin-top: 8px; position: relative; display: none; }
        .edit-message-content { min-height: 40px; max-height: 120px; overflow-y: auto; padding: 8px; border: 1px solid #e2e8f0; border-radius: 8px; background: var(--ra-primary); outline: none; }
        .edit-message-content:focus { border-color: var(--ra-primary); box-shadow: 0 0 0 2px rgba(102, 126, 234, 0.1); }
        .edit-actions { display: flex; justify-content: flex-end; gap: 8px; margin-top: 8px; }
        .edit-btn { width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; border: none; cursor: pointer; transition: all 0.2s ease; }
        .edit-cancel { background: #f8f9fa; color: #6c757d; }
        .edit-cancel:hover { background: #e9ecef; transform: scale(1.1); }
        .edit-save { background: var(--ra-primary); color: white; }
        .edit-save:hover { background: #5a6fd8; transform: scale(1.1); }
        .toggle-icon2 { position: absolute; top: 15px; right: 15px; cursor: pointer; color: var(--ra-primary); font-size: 18px; z-index: 1000; background: rgba(255, 255, 255, 0.9); width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15); transition: all 0.3s ease; }
        .toggle-icon2:hover { background: var(--ra-primary); color: white; transform: scale(1.1); }
        .toggle-icon2.rotated { transform: rotate(180deg); }
        .toggle-icon2.rotated:hover { transform: rotate(180deg) scale(1.1); }
        .collapsed-section { display: flex; justify-content: center; align-items: center; height: 100%; }
        .deleted-message { background: var(--ra-primary); border-radius: 12px; padding: 12px 16px; color: #ffffff; font-style: italic; font-size: 14px; }
        .undo-btn { background: #ffffff; color: var(--ra-primary); border: none; border-radius: 6px; padding: 4px 12px; font-size: 12px; margin-left: 8px; cursor: pointer; transition: all 0.2s ease; }
        .message-sender-info { display: flex; align-items: center; gap: 8px; margin-bottom: 4px; font-size: 14px; }
        .compact-message { display: flex; align-items: flex-start; gap: 8px; margin-bottom: 8px; width: 100%; }
        .compact-sent { justify-content: flex-end; }
        .compact-received { justify-content: flex-start; }
        .compact-bubble { padding: 5px; border-radius: 16px; max-width: 100%; word-wrap: break-word; position: relative; font-size: 14px; }
        .compact-sent-bubble { background: var(--ra-primary); color: white; border-bottom-right-radius: 4px; }
        .compact-received-bubble { background: #ffffff; color: #333; border-bottom-left-radius: 4px; border: 1px solid #e9ecef; }
        .compact-actions { position: absolute; top: 2px; right: 2px; opacity: 0; transition: opacity 0.2s ease; }
        .compact-bubble:hover .compact-actions { opacity: 1; }
        .image-modal { display: none; position: fixed; z-index: 9999; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.9); backdrop-filter: blur(5px); }
        .modal-content { display: flex; flex-direction: column; align-items: center; justify-content: center; height: 100%; position: relative; }
        .modal-image { max-width: 90%; max-height: 80%; object-fit: contain; transition: transform 0.3s ease; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3); border-radius: 8px; }
        .modal-controls { position: absolute; bottom: 30px; display: flex; gap: 10px; background: rgba(0, 0, 0, 0.7); padding: 12px 20px; border-radius: 30px; backdrop-filter: blur(10px); }
        .modal-btn { width: 44px; height: 44px; border-radius: 50%; display: flex; align-items: center; justify-content: center; background: var(--ra-primary); color: white; border: none; cursor: pointer; transition: all 0.3s ease; font-size: 18px; }
        .modal-btn:hover { background: #5a6fd8; transform: scale(1.1); }
        .modal-btn:active { transform: scale(0.95); }
        .close-modal { position: absolute; top: 20px; right: 30px; font-size: 40px; color: white; cursor: pointer; z-index: 10000; background: rgba(0, 0, 0, 0.5); width: 50px; height: 50px; border-radius: 50%; display: flex; align-items: center; justify-content: center; transition: all 0.3s ease; }
        .close-modal:hover { background: rgba(255, 255, 255, 0.2); transform: scale(1.1); }
        .zoom-info { position: absolute; top: 20px; left: 30px; color: white; font-size: 16px; background: rgba(0, 0, 0, 0.5); padding: 8px 16px; border-radius: 20px; backdrop-filter: blur(10px); }
        #messageEditor:focus { outline: none !important; border: none !important; }
        .side-arrow { position: absolute; top: 50%; transform: translateY(-50%); width: 50px; height: 50px; border-radius: 50%; background: rgba(255, 255, 255, 0.15); backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.2); color: white; font-size: 24px; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: all 0.3s ease; z-index: 1000; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3); }
        .side-arrow:hover { background: rgba(255, 255, 255, 0.25); transform: translateY(-50%) scale(1.1); box-shadow: 0 6px 20px rgba(0, 0, 0, 0.4); }
        .side-arrow:active { transform: translateY(-50%) scale(0.95); }
        .prev-arrow { left: 20px; }
        .next-arrow { right: 20px; }
        .side-arrow i { filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.3)); }
        .arrow-disabled { opacity: 0.5; cursor: not-allowed; }
        .arrow-disabled:hover { background: rgba(255, 255, 255, 0.15); transform: translateY(-50%) scale(1); box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3); }
    </style>

    <script>
        const currentUserId = @json(auth()->user()->employeerole_id);
        const isProjectLead = @json($statistics['isProjectLead']);
        const defaultImage = '/images/admin_default.jpg';
        let currentChatType = null;
        let currentChatId = null;
        let currentChatTitle = null;
        let replyToMessageId = null;
        let editingMessageId = null;
        let currentImageIndex = 0;
        let imageList = [];
        let currentZoomLevel = 100;
        let activeFormatButtons = new Set();

        function formatText(command) {
            const editor = document.getElementById('messageEditor');
            editor.focus();
            const isActive = document.queryCommandState(command);
            document.execCommand(command, false, null);
            editor.focus();
            updateButtonActiveState(command, !isActive);
        }

        function updateButtonActiveState(command, isActive) {
            const button = document.querySelector(`[onclick*="${command}"]`);
            if (!button) return;
            if (isActive) {
                button.classList.add('active');
                activeFormatButtons.add(command);
            } else {
                button.classList.remove('active');
                activeFormatButtons.delete(command);
            }
        }

        function toggleSubtasks(taskId) {
            const subtaskElement = document.getElementById(`subtasks-${taskId}`);
            const iconElement = document.getElementById(`icon-${taskId}`);
            if (subtaskElement.style.display === 'none') {
                subtaskElement.style.display = 'block';
                iconElement.className = 'bi bi-chevron-up text-primary small';
            } else {
                subtaskElement.style.display = 'none';
                iconElement.className = 'bi bi-chevron-down text-primary small';
            }
        }

        function handleFileSelection(files) {
            const fileInfo = document.getElementById('fileInfo');
            if (files.length > 0) {
                fileInfo.textContent = `${files.length} file(s) selected`;
                fileInfo.className = 'text-sm text-success fw-semibold';
            } else {
                fileInfo.textContent = 'No files selected';
                fileInfo.className = 'text-sm text-gray-600';
            }
        }

        function loadTaskChat(taskId, taskName, chatWithName, chatWithImage, chatWithDesignation, chatWithEmail) {
            currentChatType = 'task';
            currentChatId = taskId;
            currentChatTitle = taskName;
            document.getElementById('chatSectionHeader').style.display = 'flex';
            document.getElementById('currentChatTitle').textContent = taskName;
            document.getElementById('currentAssignee').style.display = 'block';
            document.getElementById('chatInputContainer').style.display = 'block';
            updateChatPartnerInfo(chatWithName, chatWithImage, chatWithDesignation, chatWithEmail);
            setActiveItem('task', taskId);
            fetchMessages();
        }

        function loadSubtaskChat(subtaskId, subtaskName, chatWithName, chatWithImage, chatWithDesignation, chatWithEmail) {
            currentChatType = 'subtask';
            currentChatId = subtaskId;
            currentChatTitle = subtaskName;
            document.getElementById('chatSectionHeader').style.display = 'flex';
            document.getElementById('currentChatTitle').textContent = subtaskName;
            document.getElementById('currentAssignee').style.display = 'block';
            document.getElementById('chatInputContainer').style.display = 'block';
            updateChatPartnerInfo(chatWithName, chatWithImage, chatWithDesignation, chatWithEmail);
            setActiveItem('subtask', subtaskId);
            fetchMessages();
        }

        function updateChatPartnerInfo(chatWithName, chatWithImage, chatWithDesignation, chatWithEmail) {
            const imageSrc = chatWithImage ? '/employee_images/' + chatWithImage : defaultImage;
            const dynamicAssignee = document.getElementById('dynamicAssignee');
            const roleTitle = isProjectLead ? 'Chatting with' : 'Project Lead';
            dynamicAssignee.innerHTML = `
                <div class="text-center mb-2">
                    <h6 class="mb-1 fw-bold text-muted">${roleTitle}</h6>
                    <img src="${imageSrc}" alt="${chatWithName}" class="user-avatar mb-3" onerror="this.src='${defaultImage}'">
                    <h6 class="mb-1 fw-bold text-gray-900">${chatWithName}</h6>
                    <p class="text-muted mb-1 small">${chatWithEmail}</p>
                    <p class="text-muted small">${chatWithDesignation}</p>
                </div>
            `;
            const assigneeInfo = document.getElementById('assigneeInfo');
            assigneeInfo.innerHTML = `
                <img src="${imageSrc}" alt="${chatWithName}" class="user-avatar-small me-3" onerror="this.src='${defaultImage}'">
                <div>
                    <div class="fw-bold text-sm text-gray-900">${chatWithName}</div>
                    <div class="text-gray-600" style="font-size:11.5px">${chatWithDesignation}</div>
                </div>
            `;
        }

        function setActiveItem(itemType, itemId) {
            document.querySelectorAll('.task-item').forEach(item => item.classList.remove('active'));
            document.querySelectorAll('.subtask-item').forEach(item => item.classList.remove('active'));
            if (itemType === 'task') {
                const taskElement = document.getElementById(`task-${itemId}`);
                if (taskElement) taskElement.classList.add('active');
            } else if (itemType === 'subtask') {
                const subtaskElement = document.getElementById(`subtask-${itemId}`);
                if (subtaskElement) subtaskElement.classList.add('active');
            }
        }

        function fetchMessages() {
            if (!currentChatType || !currentChatId) return;
            const url = currentChatType === 'task'
                ? `/dashboard/employees/etaskchat/task/${currentChatId}/messages`
                : `/dashboard/employees/etaskchat/subtask/${currentChatId}/messages`;
            fetch(url)
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        displayMessages(data.messages);
                    } else {
                        alert('Error: ' + (data.error || 'Failed to fetch messages'));
                    }
                })
                .catch(error => {
                    alert('Failed to fetch messages: ' + error.message);
                });
        }

   function displayMessages(messages) {
    const chatMessages = document.getElementById('chatMessages');
    if (messages.length === 0) {
        chatMessages.innerHTML = `
            <div class="text-center text-gray-500 align-content-center h-100">
                <i class="bi bi-chat-dots display-4 text-gray-300 mb-3"></i>
                <p class="text-lg text-gray-600 mt-3">No messages yet</p>
                <p class="text-sm text-gray-500">Be the first to start the conversation!</p>
            </div>
        `;
        return;
    }
    chatMessages.innerHTML = '';
    messages.forEach(message => {
        const messageId = message.chat_id;
        const isSentByMe = message.sender_id === currentUserId;
        const time = new Date(message.created_at).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
        const messageElement = document.createElement('div');
        messageElement.className = `compact-message ${isSentByMe ? 'compact-sent' : 'compact-received'}`;
        messageElement.id = `message-${messageId}`;

        let filesHtml = '';
        if (message.files_info && message.files_info.length > 0) {
            message.files_info.forEach(file => {
                if (file.is_image) {
                    filesHtml += `
                        <div class="file-attachment position-relative mt-2">
                            <img src="${file.url}" alt="${file.name}" onclick="openImageModal('${file.url}', '${file.name}')" style="max-width: 300px; border-radius: 8px;">
                        </div>
                    `;
                } else {
                    filesHtml += `
                        <div class="file-attachment mt-2">
                            <div class="file-preview">
                                <i class="bi ${file.icon} file-icon"></i>
                                <div class="file-info">
                                    <div class="file-name">${file.name}</div>
                                </div>
                                <a href="${file.url}" download="${file.name}" class="download-btn">
                                    <i class="bi bi-download"></i>
                                </a>
                            </div>
                        </div>
                    `;
                }
            });
        }

        // Check if message has text content
        const hasText = message.message && message.message.trim().length > 0;

        // Show edit button only if there's text (regardless of whether there are files or not)
        const showEditButton = isSentByMe && hasText;

        messageElement.innerHTML = `
            <div class="message-content">
                <div class="message-sender-info">
                    <small class="fw-semibold ${isSentByMe ? 'text-primary' : 'text-gray-700'}">
                        ${isSentByMe ? 'You' : (message.sender ? message.sender.fullname : 'Unknown User')}
                    </small>
                    <small class="message-time text-gray-500 ms-2">${time}</small>
                </div>
                <div class="compact-bubble ${isSentByMe ? 'compact-sent-bubble' : 'compact-received-bubble'} position-relative mt-1">
                    ${filesHtml}
                    ${message.message ? `<div class="message-text mb-2 mt-3" id="message-text-${messageId}">${message.message}</div>` : ''}
                    ${showEditButton ? `
                        <div class="compact-actions">
                            <div class="dropdown">
                                <button class="dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                    <i class="bi bi-three-dots-vertical"></i>
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="#" onclick="startEditMessage(${messageId})">
                                        <i class="bi bi-pencil"></i> Edit
                                    </a></li>

                                </ul>
                            </div>
                        </div>
                    ` : ''}
                </div>
                ${showEditButton ? `
                    <div class="edit-container" id="edit-container-${messageId}" style="display: none;">
                        <div class="d-flex align-items-start gap-2 mb-2">
                            <div class="flex-grow-1">
                                <div class="input-toolbar">
                                    <div id="edit-message-${messageId}" class="w-100 border-0 bg-transparent outline-none resize-none edit-message-content" contenteditable="true">${message.message || ''}</div>
                                </div>
                            </div>
                            <div class="d-flex flex-column gap-1">
                                <button class="edit-btn edit-save" onclick="saveEditMessage(${messageId})">
                                    <i class="bi bi-check"></i>
                                </button>
                                <button class="edit-btn edit-cancel" onclick="cancelEditMessage(${messageId})">
                                    <i class="bi bi-x"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                ` : ''}
            </div>
        `;
        chatMessages.appendChild(messageElement);
    });
    chatMessages.scrollTop = chatMessages.scrollHeight;
}


// delete in chat div
// <li><a class="dropdown-item text-danger" href="#" onclick="deleteMessage(${messageId})">
                                    //     <i class="bi bi-trash"></i> Delete
                                    // </a></li>

function saveEditMessage(messageId) {
    const editContent = document.getElementById(`edit-message-${messageId}`);
    const messageText = editContent.innerHTML.trim();

    // Allow empty message if there are files attached
    const messageElement = document.getElementById(`message-${messageId}`);
    const hasFiles = messageElement.querySelector('.file-attachment') !== null;

    if (!messageText && !hasFiles) {
        alert('Message cannot be empty');
        return;
    }

    fetch(`/dashboard/employees/etaskchat/messages/${messageId}`, {
        method: 'PUT',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ message: messageText })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const messageTextElement = document.getElementById(`message-text-${messageId}`);
            const editContainer = document.getElementById(`edit-container-${messageId}`);
            if (messageTextElement) {
                if (messageText) {
                    messageTextElement.innerHTML = messageText;
                    messageTextElement.style.display = 'block';
                } else {
                    // If message text becomes empty after edit, hide the text element
                    messageTextElement.style.display = 'none';
                    // Also hide the edit button since there's no text anymore
                    const compactActions = messageElement.querySelector('.compact-actions');
                    if (compactActions) {
                        compactActions.style.display = 'none';
                    }
                }
            }
            if (editContainer) {
                editContainer.style.display = 'none';
            }
            resetEditUI();
        } else {
            alert('Error: ' + (data.error || 'Failed to update message'));
        }
    })
    .catch(error => {
        alert('Failed to update message');
    });
}
        function startEditMessage(messageId) {
            const messageElement = document.getElementById(`message-${messageId}`);
            const messageTextElement = document.getElementById(`message-text-${messageId}`);
            const editContainer = document.getElementById(`edit-container-${messageId}`);
            const editContent = document.getElementById(`edit-message-${messageId}`);
            if (messageTextElement && editContent) {
                editContent.innerHTML = messageTextElement.innerHTML;
                messageTextElement.style.display = 'none';
                editContainer.style.display = 'block';
                editContent.focus();
                editingMessageId = messageId;
                const cancelEditBtn = document.getElementById('cancelEditBtn');
                const sendMessageBtn = document.getElementById('sendMessageBtn');
                if (cancelEditBtn && sendMessageBtn) {
                    cancelEditBtn.style.display = 'inline-block';
                    sendMessageBtn.textContent = 'Save';
                    sendMessageBtn.setAttribute('onclick', `saveEditMessage(${messageId})`);
                }
            }
        }


        function cancelEditMessage() {
            if (editingMessageId) {
                const messageTextElement = document.getElementById(`message-text-${editingMessageId}`);
                const editContainer = document.getElementById(`edit-container-${editingMessageId}`);
                if (messageTextElement) {
                    messageTextElement.style.display = 'block';
                }
                if (editContainer) {
                    editContainer.style.display = 'none';
                }
            }
            resetEditUI();
        }

        function resetEditUI() {
            const cancelEditBtn = document.getElementById('cancelEditBtn');
            const sendMessageBtn = document.getElementById('sendMessageBtn');
            if (cancelEditBtn && sendMessageBtn) {
                cancelEditBtn.style.display = 'none';
                sendMessageBtn.textContent = 'Send';
                sendMessageBtn.setAttribute('onclick', 'sendMessage()');
            }
            editingMessageId = null;
        }

        function deleteMessage(messageId) {
            if (!confirm('Are you sure you want to delete this message?')) return;
            fetch(`/dashboard/employees/etaskchat/messages/${messageId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                }
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const messageElement = document.getElementById(`message-${messageId}`);
                        if (messageElement) messageElement.remove();
                        fetchMessages();
                    } else {
                        alert('Error: ' + (data.error || 'Failed to delete message'));
                    }
                })
                .catch(error => {
                    alert('Failed to delete message');
                });
        }

        function toggleUserInfoSection() {
            const userInfoSection = document.getElementById('userInfoSection');
            const chatCol = document.getElementById('chatCol');
            const toggleIcon = document.getElementById('toggleIcon');
            const userInfoContent = document.querySelector('.user-info-content');
            if (userInfoSection.classList.contains('user-info-collapsed')) {
                userInfoSection.classList.remove('user-info-collapsed');
                userInfoSection.style.flex = '0 0 25%';
                userInfoSection.style.maxWidth = '25%';
                chatCol.classList.remove('col-expand-1');
                chatCol.style.flex = '0 0 50%';
                chatCol.style.maxWidth = '50%';
                userInfoContent.style.display = 'block';
                toggleIcon.classList.remove('rotated');
                toggleIcon.innerHTML = '<i class="bi bi-shuffle"></i>';
            } else {
                userInfoSection.classList.add('user-info-collapsed');
                userInfoSection.style.flex = '0 0 60px';
                userInfoSection.style.maxWidth = '60px';
                chatCol.classList.add('col-expand-1');
                chatCol.style.flex = '0 0 calc(75% - 60px)';
                chatCol.style.maxWidth = 'calc(75% - 60px)';
                userInfoContent.style.display = 'none';
                toggleIcon.classList.add('rotated');
                toggleIcon.innerHTML = '<i class="bi bi-shuffle"></i>';
            }
        }

        function openImageModal(imageSrc, imageName) {
            const modal = document.getElementById('imageModal');
            const modalImage = document.getElementById('modalImage');
            const chatMessages = document.getElementById('chatMessages');
            const allImages = chatMessages.querySelectorAll('.file-attachment img');
            imageList = [];
            allImages.forEach((img, index) => {
                imageList.push({
                    src: img.src,
                    alt: img.alt
                });
                if (img.src === imageSrc) {
                    currentImageIndex = index;
                }
            });
            modalImage.src = imageSrc;
            modalImage.alt = imageName;
            currentZoomLevel = 100;
            modalImage.style.transform = `scale(${currentZoomLevel / 100})`;
            document.getElementById('zoomInfo').textContent = `${currentZoomLevel}%`;
            modal.style.display = 'block';
            document.body.style.overflow = 'hidden';
            updateNavigationArrows();
        }

        function closeImageModal() {
            const modal = document.getElementById('imageModal');
            modal.style.display = 'none';
            document.body.style.overflow = 'auto';
            imageList = [];
            currentImageIndex = 0;
        }

        function updateNavigationArrows() {
            const prevArrow = document.getElementById('prevArrow');
            const nextArrow = document.getElementById('nextArrow');
            if (imageList.length > 1) {
                prevArrow.classList.remove('arrow-disabled');
                nextArrow.classList.remove('arrow-disabled');
            } else {
                prevArrow.classList.add('arrow-disabled');
                nextArrow.classList.add('arrow-disabled');
            }
        }

        function prevImage() {
            if (imageList.length <= 1) return;
            currentImageIndex = (currentImageIndex - 1 + imageList.length) % imageList.length;
            updateModalImage();
        }

        function nextImage() {
            if (imageList.length <= 1) return;
            currentImageIndex = (currentImageIndex + 1) % imageList.length;
            updateModalImage();
        }

        function updateModalImage() {
            const modalImage = document.getElementById('modalImage');
            modalImage.src = imageList[currentImageIndex].src;
            modalImage.alt = imageList[currentImageIndex].alt;
            currentZoomLevel = 100;
            modalImage.style.transform = `scale(${currentZoomLevel / 100})`;
            document.getElementById('zoomInfo').textContent = `${currentZoomLevel}%`;
        }

        function zoomIn() {
            if (currentZoomLevel < 300) {
                currentZoomLevel += 25;
                updateZoom();
            }
        }

        function zoomOut() {
            if (currentZoomLevel > 25) {
                currentZoomLevel -= 25;
                updateZoom();
            }
        }

        function resetZoom() {
            currentZoomLevel = 100;
            updateZoom();
        }

        function updateZoom() {
            const modalImage = document.getElementById('modalImage');
            modalImage.style.transform = `scale(${currentZoomLevel / 100})`;
            document.getElementById('zoomInfo').textContent = `${currentZoomLevel}%`;
        }

        function downloadImage() {
            const modalImage = document.getElementById('modalImage');
            const link = document.createElement('a');
            link.href = modalImage.src;
            link.download = modalImage.alt || 'image';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }

        function getMessageContent() {
            const editor = document.getElementById('messageEditor');
            return editor.innerHTML;
        }

        function sendMessage() {
            if (!currentChatType || !currentChatId) {
                alert('Please select a task or subtask first');
                return;
            }
            const messageContent = getMessageContent().trim();
            const fileInput = document.getElementById('fileInput');
            const hasFiles = fileInput.files.length > 0;
            if (!messageContent && !hasFiles) {
                alert('Please enter a message or select files to send');
                return;
            }
            const formData = new FormData();
            if (messageContent) formData.append('message', messageContent);
            if (hasFiles) {
                Array.from(fileInput.files).forEach(file => formData.append('files[]', file));
            }
            const url = currentChatType === 'task'
                ? `/dashboard/employees/etaskchat/task/${currentChatId}/send-message`
                : `/dashboard/employees/etaskchat/subtask/${currentChatId}/send-message`;
            const sendBtn = document.getElementById('sendMessageBtn');
            const originalText = sendBtn.innerHTML;
            sendBtn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i> Sending...';
            sendBtn.disabled = true;
            fetch(url, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: formData
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    sendBtn.innerHTML = originalText;
                    sendBtn.disabled = false;
                    if (data.success) {
                        document.getElementById('messageEditor').innerHTML = '';
                        fileInput.value = '';
                        document.getElementById('fileInfo').textContent = 'No files selected';
                        document.getElementById('fileInfo').className = 'text-sm text-gray-600';
                        fetchMessages();
                    } else {
                        alert('Error: ' + (data.error || 'Unknown error'));
                    }
                })
                .catch(error => {
                    sendBtn.innerHTML = originalText;
                    sendBtn.disabled = false;
                    alert('Failed to send message: ' + error.message);
                });
        }

        function cancelReply() {
            replyToMessageId = null;
            document.getElementById('replyIndicator').style.display = 'none';
        }

        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.task-name').forEach(taskElement => {
                taskElement.addEventListener('click', function (e) {
                    e.stopPropagation();
                    const taskId = this.getAttribute('data-task-id');
                    const taskName = this.getAttribute('data-task-name');
                    const chatWithName = this.getAttribute('data-chatwith-name');
                    const chatWithImage = this.getAttribute('data-chatwith-image');
                    const chatWithDesignation = this.getAttribute('data-chatwith-designation');
                    const chatWithEmail = this.getAttribute('data-chatwith-email');
                    loadTaskChat(taskId, taskName, chatWithName, chatWithImage, chatWithDesignation, chatWithEmail);
                });
            });
            document.querySelectorAll('.subtask-name').forEach(subtaskElement => {
                subtaskElement.addEventListener('click', function (e) {
                    e.preventDefault();
                    const subtaskId = this.getAttribute('data-subtask-id');
                    const subtaskName = this.getAttribute('data-subtask-name');
                    const chatWithName = this.getAttribute('data-chatwith-name');
                    const chatWithImage = this.getAttribute('data-chatwith-image');
                    const chatWithDesignation = this.getAttribute('data-chatwith-designation');
                    const chatWithEmail = this.getAttribute('data-chatwith-email');
                    loadSubtaskChat(subtaskId, subtaskName, chatWithName, chatWithImage, chatWithDesignation, chatWithEmail);
                });
            });
            document.getElementById('taskSearch').addEventListener('input', function (e) {
                const searchTerm = e.target.value.toLowerCase();
                const tasks = document.querySelectorAll('.task-item');
                tasks.forEach(task => {
                    const taskName = task.querySelector('.task-name').textContent.toLowerCase();
                    const subtaskItems = task.querySelectorAll('.subtask-name');
                    let hasMatch = taskName.includes(searchTerm);
                    subtaskItems.forEach(subtask => {
                        const subtaskName = subtask.textContent.toLowerCase();
                        if (subtaskName.includes(searchTerm)) hasMatch = true;
                    });
                    task.style.display = hasMatch ? 'block' : 'none';
                });
            });
            document.addEventListener('keydown', function (e) {
                const modal = document.getElementById('imageModal');
                if (modal.style.display === 'block') {
                    if (e.key === 'ArrowLeft') prevImage();
                    if (e.key === 'ArrowRight') nextImage();
                    if (e.key === 'Escape') closeImageModal();
                    if (e.key === '+') zoomIn();
                    if (e.key === '-') zoomOut();
                }
            });
            const messageEditor = document.getElementById('messageEditor');
            if (messageEditor) {
                messageEditor.addEventListener('click', function() {
                    document.querySelectorAll('.toolbar-btn').forEach(btn => btn.classList.remove('active'));
                    activeFormatButtons.clear();
                });
                messageEditor.addEventListener('keyup', function() {
                    document.querySelectorAll('.toolbar-btn').forEach(btn => btn.classList.remove('active'));
                    activeFormatButtons.clear();
                });
            }
            loadUnreadCounts();
            setInterval(loadUnreadCounts, 30000);
        });

        function loadUnreadCounts() {
            fetch('/dashboard/employees/etaskchat/unread-counts')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const taskCounts = data.task_unread_counts;
                        const subtaskCounts = data.subtask_unread_counts;
                        for (const [taskId, count] of Object.entries(taskCounts)) {
                            const notificationIcon = document.getElementById(`task-notification-${taskId}`);
                            if (count > 0) {
                                notificationIcon.style.display = 'block';
                            } else {
                                notificationIcon.style.display = 'none';
                            }
                        }
                        for (const [subtaskId, count] of Object.entries(subtaskCounts)) {
                            const notificationIcon = document.getElementById(`subtask-notification-${subtaskId}`);
                            if (count > 0) {
                                notificationIcon.style.display = 'block';
                            } else {
                                notificationIcon.style.display = 'none';
                            }
                        }
                    }
                })
                .catch(error => console.error('Error loading unread counts:', error));
        }
    </script>
</x-layout>

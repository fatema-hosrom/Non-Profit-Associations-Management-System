📄 **Full System Report:**
👉 [Download Full System Report](./Graduation Project Report 2.pdf)

---

🌟 **Sahem – Event, Organization, and Volunteer Management Platform**

**Sahem** is an integrated digital system designed to organize volunteer work and connect organizations with events and volunteers within a single, modern, and user-friendly platform.
The project provides a public interface for visitors and volunteers, along with an administrative dashboard for supervisors and managers, featuring a scalable design that supports future service expansion.

---

📌 **Project Idea**

Organizations need an effective way to showcase their events and manage volunteers, while volunteers are looking for a unified platform that presents events clearly and easily.
This is where **Sahem** comes in as a comprehensive solution that brings together:

• Organizations
• Events
• Volunteers
• Administration
• Public Interface

All within one structured and flexible system.

---

🎯 **Project Objectives**

• Provide a unified platform to display events and organizations.
• Simplify volunteer registration with a clear approval system.
• Enable supervisors to manage events and organizations بسهولة.
• Offer an attractive and user-friendly public interface.
• Build a strong foundation for future expansion (donations – volunteer requests – reports – mobile app).

---

🧩 **Core Features**

👤 **For Users (Visitors & Volunteers)**

• Create a volunteer account (subject to supervisor approval before activation).
• View only current and active published events.
• Browse event details (description, date, location, type).
• View the list of organizations.
• View events associated with each organization.
• Browse details of organization-related events.

---

🛠️ **For Supervisors & Admins**

• Manage organizations (create – update – delete – view).
• Manage events (create – update – delete – publish – unpublish).
• Review volunteer applications and approve or reject them.
• Manage volunteer requirements for each event.
• Full control over event visibility for users.

---

🏗️ **Architecture Overview**

1. **Public Frontend**

• Landing page
• Organizations list
• Events list
• Event details page
• Organization details page
• Volunteer registration page

---

2. **Admin Dashboard**

• Organization management
• Event management
• Volunteer management
• Approval system
• Publishing control

---

3. **Database Layer**

The platform relies on clearly structured relational tables, أبرزها:

**ORGANIZATION**
Organization data

**ORGANIZATION_EVENT**
Events linked to organizations

**ORGANIZATION_ACTIVITY**
Event details (description – date – type – publication status)

**ACTIVITY_VOLUNTEER_REQUIREMENTS**
Volunteer requirements for each event

**VOLUNTEER**
Volunteer data with approval system

---

🔄 **Workflow**

1. **Volunteer Registration**

• User submits their information via the registration form.
• Data is stored with a *pending* status.

---

2. **Supervisor Review**

• Supervisor reviews the request.
• Decides to approve or reject.
• Account status is updated accordingly.

---

3. **Account Activation**

• If approved → the volunteer can participate.
• If rejected → the reason for rejection is shown.

---

🚀 **Roadmap**

• Add a donation system
• Add volunteer requests linked to events
• Advanced reporting system
• Mobile app for volunteers
• Dashboard for supporting government entities


---

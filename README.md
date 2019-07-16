# Student Portal
(A Student Portal made using HTML, CSS, PHP, mySQL, JavaScript)

A simplified Student Portal featuring Online Assignments (setting up, taking up and analysis), Messaging, Finding users, adding Announcements and lot more. Before setting it up for yourself, enjoy a **live demo** by [clicking here](http://projects.subhalingam.heliohost.org/sportal/) after reading [this](#demo-instructions)

## Features
### Roles
This portal is for everyone-***Admin***, ***Faculties*** and ***Students***. *Admin* has an Admin Panel for doing some administrative work. Let's move on!
### Assignments
This is the highlight of this project! All the three groups have some role to play:
<details>
	<summary><b>Admin</b></summary>
	<p>
		<ul>
			<li>Accept/Reject Assignment requests from faculty</li>
			<li>Add an assignment for a batch</li>
			<li>Make changes to timings and key</li>
		</ul>
	</p>
</details>
<details>
	<summary><b>Faculty</b></summary>
	<p>
		<ul>
			<li>Request Assignment for one of his batch (one at a time)</li>
			<li>Add, modify and delete Questions</li>
			<li>Submit (answer) key changes after the end-time</li>
			<li>Analyse (in detail) the performance of the batch
		</ul>
		<b>Note:</b> Batch marks will be available only after finalising/freezing the answer key after the end-time
		<i>It is recommended to upload the correct answers after the end-time</i>
	</p>
</details>
<details>
	<summary><b>Student</b></summary>
	<p>
		<ul>
			<li>Take up an assignment <i>online</i> with timer set in server</li>
			<li>Analyse his/her performance after taking up the assignment</li>
		</ul>
	</p>
</details>

For now, questions can be of Single Correct, One or More than one correct, Matrix matching, Single-digit Integer or Numerical type. The Numerical type answers can also support a range of answers, if required.

### Messaging
Make friends, interact with your faculties and get your doubts cleared! Its a nice place for some fun too, but beaware that the messages aren't encrypted and can be seen by the Admin at any time!

### Announcements
Many news keep coming up and this is the place to dump all of them! These include the auto-generated announcements for new assignments for student, official announcements, some message by the faculty, etc. Keep checking for updates so that you donot miss any of them!

### Find user
Everyone in this world is identified by their unique username to avoid confusions and sometimes some random ID can popup in your messages. Find who that is from this *Find user* page. You can also search for the username by the name. Found the person you want? You can drop a message-just press on that *Message* button on the side!

### Profile
View your profile, update your contact details, change your password and update your profile picture. There is nothing else here!

### Admin Panel
These are the special options for the Admin. Add/Remove users, batches, faculty handling the batches and Reset password for the users!

## Installation
### Requirements
- Web Server
- PHP 7.1 *(recommended)*
- mySQL

### Setting up
Setting up is easy with our [setup.php](setup.php) file. Enter your DB details, server's timezone and Admin User details and you can start using the portal.

## What's New
- Added CSS to make everything look beautiful
- Fixed few security issues
- Minor performance improvements

## What's Next
- Redesigning Home and Messages page
- A timetable and attendance system to look at upcoming classes and mark attendance online
- Encryption of messages, questions, responses.

## Demo Instructions
1. Login as admin using 'admin' as username & password
2. Have a look at admin features. Explore them! **You don't have the rights to update password and update contact details for this account**. Similarly, **removing users** is disabled in demo.
3. You can create your own account in the **Admin Panel>Add user** page.

#### Do not forget to take up an assignment as a student!
